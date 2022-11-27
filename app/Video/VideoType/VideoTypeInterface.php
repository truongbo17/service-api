<?php

namespace App\Video\VideoType;

use App\Exceptions\MakeVideoException;
use App\Models\Video;
use Throwable;

interface VideoTypeInterface
{
    /**
     * Get video from database
     *
     * @return array
     */
    public function callFromDatabase(): array;

    /**
     * Get video from Latest (directly from the api)
     *
     * @return array
     * */
    public function callFromLatest(): array;

    /**
     * Get video
     *
     * @return array
     * */
    public function get(): array;

    /**
     * Handle list video by duration (return video)
     *
     * @throws MakeVideoException
     * @throws Throwable
     */
    public function handleDuration();

    /**
     * Save video to storage
     *
     * @param Video $video
     * @param array $item
     * @return string
     * */
    public function saveVideo(Video $video, array $item): string;

    /**
     * Save video export to model
     * */
    public function saveModelVideoExport();
}
