<?php

namespace App\Video\VideoType;

use App\Exceptions\MakeVideoException;
use App\Libs\IdToPath\IdToPath;
use App\Models\Video;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Storage;

abstract class VideoTypeAbstract implements VideoTypeInterface
{
    /**
     * @var ?Collection $list_videos
     * */
    protected ?Collection $list_videos = null;

    /**
     * If duration doesn't meet demand, this will be the number of retries to get the right duration. The higher this value is set, the slower the program will run or it may die
     *
     * @const TRY_GET_VIDEO_DURATION
     * @var int $check_try_get_video
     * */
    private const TRY_GET_VIDEO_DURATION = 100;
    private static int $check_try_get_video = 0;

    /**
     * @const SLEEP_WAIT_GET_VIDEO
     * */
    protected const SLEEP_WAIT_GET_VIDEO = 1;

    /**
     * Check condition , word score,... before handle duration
     *
     * @param Collection $list_video
     * @return Collection
     * */
    protected function beforeHandle(Collection $list_video): Collection
    {
        return $list_video
            ->where('duration', '<=', config('video.ignore_duration_video_trending'))
            ->unique();
    }

    /**
     * Check the number of attempts to get the video
     *
     * @throws MakeVideoException
     */
    protected function checkTryGetVideo()
    {
        self::$check_try_get_video++;
        if (self::$check_try_get_video >= self::TRY_GET_VIDEO_DURATION) {
            throw new MakeVideoException("The number of attempts to get video has been exceeded. This is not an error. You can change the \$count(total videos retrieved) parameter passed in.");
        }
    }

    /**
     * Save video to storage
     *
     * @param Video $video
     * @param array $item
     * @return string
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws NotFoundExceptionInterface
     */
    public function saveVideo(Video $video, array $item): string
    {
        $video_file_name = IdToPath::make($video->id, 'mp4');
        Storage::disk(config('crawl.disk_video'))->put($video_file_name,
            app('client')->get($item['wmplay'])->getBody()->getContents());
        return $video_file_name;
    }

    /**
     * Get sum duration of list video
     *
     * @return int
     * */
    public function getSumDuration(): int
    {
        return $this->list_videos?->sum('duration') ?? 0;
    }
}
