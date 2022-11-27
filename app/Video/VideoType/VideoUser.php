<?php

namespace App\Video\VideoType;

use App\Exceptions\InvalidUniqueUserException;
use App\Models\Video;
use App\Video\Traits\CheckSizeVideo;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class VideoUser extends VideoTypeAbstract
{
    use CheckSizeVideo;

    /**
     * Construct class VideoTrending
     *
     * @param int $duration
     * @param bool $latest
     * @param bool $force
     * @param array $args
     * @throws InvalidUniqueUserException
     */
    public function __construct(
        private readonly int   $duration,
        private readonly bool  $latest,
        private readonly bool  $force,
        private readonly array $args = [],
    )
    {
        if (!isset($this->args['unique_user'])) {
            throw new InvalidUniqueUserException();
        }

        if ($this->latest) {
            $this->list_videos = collect($this->callFromLatest());
        } else {
            $this->list_videos = collect($this->callFromDatabase());
        }

        $this->list_videos = $this->beforeHandle(list_video: $this->list_videos);
        $this->handleDuration();
    }


    /**
     * @return array
     */
    public function callFromDatabase(): array
    {
        // TODO: Implement callFromDatabase() method.
    }

    /**
     * Get video from Latest (directly from the api)
     *
     * @return array
     * */
    public function callFromLatest(): array
    {
        try {
            $data_videos = TiktokWmApi::getVideosByUser(method: 'GET', unique_id: $this->args['unique_user'], count: 35, cursor: 0);
//            if (count($data_videos) < 1) {
//                $data_videos = TiktokApiNature::getTrending(method: 'GET', count: 35);
//            }
            return $data_videos;
        } catch (Exception $exception) {
            Log::error($exception);
            return [];
        }
    }

    /**
     * @return array
     */
    public function get(): array
    {
        // TODO: Implement get() method.
    }

    /**
     * @return mixed
     */
    public function handleDuration()
    {
        // TODO: Implement handleDuration() method.
    }

    /**
     * @param Video $video
     * @param array $item
     * @return string
     */
    public function saveVideo(Video $video, array $item): string
    {
        // TODO: Implement saveVideo() method.
    }

    /**
     * @return mixed
     */
    public function saveModelVideoExport()
    {
        // TODO: Implement saveModelVideoExport() method.
    }
}
