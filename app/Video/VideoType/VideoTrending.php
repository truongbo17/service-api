<?php

namespace App\Video\VideoType;

use App\Enums\TypeVideoEnum;
use App\Exceptions\MakeVideoException;
use App\Libs\DiskPathTools\DiskPathInfo;
use App\Libs\IdToPath\IdToPath;
use App\Models\Author;
use App\Models\Musics;
use App\Models\Trending;
use App\Models\Video;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Video\Traits\CheckSizeVideo;
use Log;
use Storage;
use Throwable;
use DB;
use TiktokApiNature;
use TiktokWmApi;

class VideoTrending extends VideoTypeAbstract
{
    use CheckSizeVideo;

    /**
     * @var array $list_id_video_trending
     * List id video used for create trending
     * */
    private array $list_id_video_trending = [];

    /**
     * @var array $list_video_file
     * List file video used for create trending
     * */
    private array $list_video_file = [];

    /**
     * @var Trending|Model|null $trending
     * Save model Trending created
     * */
    private Trending|Model|null $trending = null;

    /**
     * Construct class VideoTrending
     *
     * @param int $duration
     * @param bool $latest
     * @param bool $force
     * @param array $args
     * @throws MakeVideoException
     * @throws Throwable
     */
    public function __construct(
        private readonly int   $duration,
        private readonly bool  $latest,
        private readonly bool  $force,
        private readonly array $args = [],
    )
    {
        if ($this->latest) {
            $this->list_videos = collect($this->callFromLatest());
        } else {
            $this->list_videos = collect($this->callFromDatabase());
        }

        $this->list_videos = $this->beforeHandle(list_video: $this->list_videos);
        $this->handleDuration();
    }

    /**
     * Handle list video by duration (return video)
     *
     * @throws MakeVideoException
     * @throws Throwable
     */
    public function handleDuration()
    {
        $sum_duration = $this->getSumDuration();
        if ($this->duration - (int)config('video.in_the_previous_period') <= $sum_duration
            && $sum_duration <= $this->duration + (int)config('video.in_the_following_period')) {
            //Handle
            $this->latest ? $this->handleWithDatabase() : $this->handleWithoutDatabase();
        } else if ($this->duration - (int)config('video.in_the_previous_period') >= $sum_duration) {
            //If the video length is not enough continue to get data
            get_data_video:
            $this->checkTryGetVideo();
            if ($this->latest) {
                $list_videos = collect($this->callFromLatest());
            } else {
                $list_videos = collect($this->callFromDatabase());
            }
            $list_videos = $this->beforeHandle(list_video: $list_videos);

            while ($this->duration - (int)config('video.in_the_previous_period') >= $this->getSumDuration() && $list_videos->count() > 0) {
                $this->list_videos->push($list_videos->first());
                $list_videos->shift();
                if ($list_videos->count() < 1) {
                    sleep(self::SLEEP_WAIT_GET_VIDEO);
                    goto get_data_video;
                }
            }
            $this->latest ? $this->handleWithDatabase() : $this->handleWithoutDatabase();
        } else if ($this->duration + (int)config('video.in_the_following_period') <= $sum_duration) {
            //If the video length is exceeded
            while ($this->getSumDuration() >= $this->duration + (int)config('video.in_the_following_period')) {
                $this->list_videos->pop();
            }
            $this->latest ? $this->handleWithDatabase() : $this->handleWithoutDatabase();
        }
    }

    /**
     * Call from database, don't use save database
     * */
    private function handleWithoutDatabase()
    {
        $this->list_id_video_trending = $this->list_videos->pluck('id')->toArray();
        $this->list_video_file = $this->list_videos->pluck('storage_file')->toArray();

        $this->saveModelVideoExport();
    }

    /**
     * Save video export to model
     * */
    public function saveModelVideoExport()
    {
        if (count($this->list_id_video_trending) > 0 && count($this->list_video_file) > 0) {
            $this->trending = Trending::create([
                'video_id' => json_encode($this->list_id_video_trending),
                'duration' => $this->getSumDuration() ?? 0,
                'type_video' => TypeVideoEnum::TRENDING,
            ]);
        }
    }

    /**
     * Handle insert,update video,author,trending to database
     * @throws Throwable
     */
    private function handleWithDatabase()
    {
        try {
            $this->list_videos->each(function ($item, $key) {
                DB::transaction(function () use ($item, $key) {
                    $music = Musics::firstOrCreate(
                        [
                            'music_id' => $item['music_info']['id']
                        ],
                        [
                            'play_url' => $item['music_info']['play'] ?? $item['music_info']['playUrl'],
                            'title' => $item['music_info']['title'],
                            'duration' => $item['music_info']['duration'],
                        ]
                    );

                    $author = Author::firstOrCreate(
                        [
                            'author_id' => $item['author']['id']
                        ],
                        [
                            'unique_id' => $item['author']['unique_id'] ?? $item['author']['uniqueId'],
                            'nickname' => $item['author']['nickname']
                        ]
                    );

                    $video = Video::updateOrCreate(
                        [
                            'video_id' => $item['video_id']
                        ],
                        [
                            'music_id' => $music->id,
                            'author_id' => $author->id,
                            'region' => $item['region'] ?? null,
                            'title' => $item['title'],
                            'hash_title' => hash('sha256', $item['title']),
                            'duration' => $item['duration'] ?? 0,
                            'play_count' => $item['play_count'] ?? 0,
                            'digg_count' => $item['digg_count'] ?? 0,
                            'comment_count' => $item['comment_count'] ?? 0,
                            'share_count' => $item['share_count'] ?? 0,
                            'download_count' => $item['download_count'] ?? 0,
                            'create_time' => $item['create_time'],
                        ]
                    );

                    $video_file_name = IdToPath::make($video->id, 'mp4');
                    $video_disk_path = new DiskPathInfo(config('crawl.disk_video'), $video_file_name);
                    $video_disk_path->put(app('client')->get($item['wmplay'])->getBody()->getContents());

                    $video->storage_file = $video_disk_path;

                    // Check size video (width , height, frame)
                    $result_check = $this->handleCheck(video_path: $video_file_name,
                        disk: config('crawl.disk_video'),
                        width_check: config('video.allow_size_video_export.width'),
                        height_check: config('video.allow_size_video_export.height'));
                    if ($result_check['result_check']) {
                        $this->list_id_video_trending[] = $video->id;
                        $this->list_video_file[] = $video_file_name;
                        $video->is_trending = 1;
                    } else {
                        // Remove current item in list video
                        $this->list_videos->forget($key);
                    }

                    $video->width = $result_check['width'];
                    $video->height = $result_check['height'];
                    $video->frame = $result_check['frame'];

                    $video->save();
                });
            });

            $this->saveModelVideoExport();
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Get video from Latest (directly from the api)
     *
     * @return array
     * */
    public function callFromLatest(): array
    {
        try {
            $data_videos = TiktokWmApi::getTrendingVideo(method: 'GET', region: 'VN', count: 35);
            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getTrending(method: 'GET', count: 35);
            }
            return $data_videos;
        } catch (Exception $exception) {
            Log::error($exception);
            return [];
        }
    }

    /**
     * Get video from database
     *
     * @return array
     */
    public function callFromDatabase(): array
    {
        $video = Video::whereNotNull('storage_file')
            ->when($this->force, function ($query) {
                return $query->where('is_trending', 0);
            })
            ->where('width', config('video.allow_size_video_export.width'))
            ->where('height', config('video.allow_size_video_export.height'))
            ->where('frame', config('video.allow_size_video_export._frame_rate'))
            ->latest('create_time')
            ->limit(10);

        $video->update([
            'is_trending' => 1,
        ]);

        return $video->get()->toArray();
    }

    /**
     * Get video trending
     *
     * @return array
     * */
    public function get(): array
    {
        return [
            'video' => $this->trending,
            'list_video_file' => $this->list_video_file
        ];
    }
}
