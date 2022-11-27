<?php

namespace App\Video;

use App\Enums\TypeVideoEnum;
use App\Exceptions\InValidDurationVideoException;
use App\Exceptions\InValidTypeVideoException;
use App\Exceptions\MakeVideoException;
use App\Libs\DiskPathTools\DiskPathInfo;
use App\Libs\IdToPath\IdToPath;
use App\Video\VideoType\VideoHashTag;
use App\Video\VideoType\VideoSound;
use App\Video\VideoType\VideoTrending;
use App\Video\VideoType\VideoTypeInterface;
use App\Video\VideoType\VideoUser;
use FFMpeg;
use Log;
use Throwable;

class MakeVideo
{
    /**
     * @var VideoTypeInterface $videos
     * */
    private VideoTypeInterface $videos;

    /**
     * @throws InValidTypeVideoException|InValidDurationVideoException
     * @throws MakeVideoException|Throwable
     */
    public function __construct(
        public string          $type,
        public int             $duration = 0,
        private readonly bool  $latest = true,
        private readonly bool  $force = true,
        private readonly array $args = [],
    )
    {
        $this->validateConstruct();
        $this->videos = $this->getVideoByType();
    }

    /**
     * Check input video type and duration
     *
     * @throws InValidTypeVideoException
     * @throws InValidDurationVideoException
     */
    public function validateConstruct(): void
    {
        if (!TypeVideoEnum::hasValue($this->type)) {
            throw new InValidTypeVideoException("Type video invalid , Allow type in Enum : " . TypeVideoEnum::class);
        }
        if ($this->duration < config('video.min_duration_video')) {
            throw new InValidDurationVideoException("The shortest length of the video must be : " . config('video.min_duration_video') . "s");
        }
    }

    /**
     * Switch to type video
     *
     * @return VideoTypeInterface
     *
     * @throws MakeVideoException
     * @throws Throwable
     */
    private function getVideoByType(): VideoTypeInterface
    {
        return match ($this->type) {
            TypeVideoEnum::TRENDING => new VideoTrending(duration: $this->duration, latest: $this->latest, force: $this->force, args: $this->args),
            TypeVideoEnum::HASHTAG => new VideoHashTag(duration: $this->duration, latest: $this->latest, force: $this->force, args: $this->args),
            TypeVideoEnum::USER => new VideoUser(duration: $this->duration, latest: $this->latest, force: $this->force, args: $this->args),
            TypeVideoEnum::SOUND => new VideoSound(duration: $this->duration, latest: $this->latest, force: $this->force, args: $this->args),
        };
    }

    /**
     * Get config disk by type
     *
     * @return string
     */
    private function getDiskByType(): string
    {
        return match ($this->type) {
            TypeVideoEnum::TRENDING => config('crawl.disk_save_video_trending'),
            TypeVideoEnum::HASHTAG => config('crawl.disk_save_video_hashtag'),
            TypeVideoEnum::USER => config('crawl.disk_save_video_user'),
            TypeVideoEnum::SOUND => config('crawl.disk_save_video_sound'),
        };
    }

    /**
     * Make video by list video trending...
     * Check size video...
     */
    public function make(): mixed
    {
        try {
            $model_video = $this->videos->get();
            $list_video_file = $model_video['list_video_file'];
            $video_model = $model_video['video'];

            if (is_null($video_model) || count($list_video_file) < 1) {
                throw new MakeVideoException("An error occurred, please try again or check file log...");
            }

            $video_file = IdToPath::make($video_model->id, 'mp4');
            FFMpeg::fromDisk(config('crawl.disk_video'))
                ->open($list_video_file)
                ->export()
                ->toDisk($this->getDiskByType())
                ->concatWithoutTranscoding()
                ->save($video_file);

            $video_disk_path = new DiskPathInfo($this->getDiskByType(), $video_file);

            $video_model->storage_video = $video_disk_path;
            $video_model->save();

            return $video_model;
        } catch (MakeVideoException $videoException) {
            Log::error($videoException);
        }
        return false;
    }
}
