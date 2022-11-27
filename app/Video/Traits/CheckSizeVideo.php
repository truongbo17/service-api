<?php

namespace App\Video\Traits;

use Exception;
use FFMpeg;
use Log;

trait CheckSizeVideo
{
    private int $CHECK_DEFAULT_VALUE = 0;
    private string $CHECK_DEFAULT_VALUE_FRAME = "";

    /**
     * Check width , height video. Just combine videos of the same size (Using FFMPEG and Storage Laravel)
     *
     * @param array|string $videos_path
     * @param string $disk
     * @param int $width_check
     * @param int $height_check
     * @return array
     */
    public function checkSize(array|string $videos_path, string $disk, int $width_check, int $height_check): array
    {
        if (is_string($videos_path)) $videos_path = (array)$videos_path;

        $temp_array_video = [];
        foreach ($videos_path as $video) {
            if ($this->handleCheck($videos_path, $disk, $width_check, $height_check)['result_check'] ?? false) {
                $temp_array_video[] = $video;
            }
        }
        return $temp_array_video;
    }

    /**
     * Handle check size video
     * @param string $video_path
     * @param string $disk
     * @param int $width_check
     * @param int $height_check
     * @return array
     */
    public function handleCheck(string $video_path, string $disk, int $width_check, int $height_check): array
    {
        try {
            $ffmpeg = FFMpeg::fromDisk($disk)
                ->open($video_path)
                ->getVideoStream();
            if ($ffmpeg->get('width', $this->CHECK_DEFAULT_VALUE) == $width_check
                && $ffmpeg->get('height', $this->CHECK_DEFAULT_VALUE) == $height_check
                && $ffmpeg->get('r_frame_rate', $this->CHECK_DEFAULT_VALUE) == config('video.allow_size_video_export._frame_rate')
                && $ffmpeg->get('avg_frame_rate', $this->CHECK_DEFAULT_VALUE) == config('video.allow_size_video_export._frame_rate')) {
                return [
                    'result_check' => true,
                    'width'        => $ffmpeg->get('width', $this->CHECK_DEFAULT_VALUE),
                    'height'       => $ffmpeg->get('height', $this->CHECK_DEFAULT_VALUE),
                    'frame'        => $ffmpeg->get('r_frame_rate',
                        $ffmpeg->get('avg_frame_rate', $this->CHECK_DEFAULT_VALUE_FRAME)),
                ];
            } else {
                return [
                    'result_check' => false,
                    'width'        => $ffmpeg->get('width', $this->CHECK_DEFAULT_VALUE),
                    'height'       => $ffmpeg->get('height', $this->CHECK_DEFAULT_VALUE),
                    'frame'        => $ffmpeg->get('r_frame_rate',
                        $ffmpeg->get('avg_frame_rate', $this->CHECK_DEFAULT_VALUE_FRAME)),
                ];
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
        return [
            'result_check' => false,
            'width'        => $this->CHECK_DEFAULT_VALUE,
            'height'       => $this->CHECK_DEFAULT_VALUE,
            'frame'        => $this->CHECK_DEFAULT_VALUE_FRAME,
        ];
    }
}
