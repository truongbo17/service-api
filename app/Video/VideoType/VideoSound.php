<?php

namespace App\Video\VideoType;

use App\Exceptions\MakeVideoException;
use App\Models\Video;
use Throwable;

class VideoSound
{

    /**
     * @return array
     */
    public function callFromDatabase(): array
    {
        // TODO: Implement callFromDatabase() method.
    }

    /**
     * @return array
     */
    public function callFromLatest(): array
    {
        // TODO: Implement callFromLatest() method.
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
