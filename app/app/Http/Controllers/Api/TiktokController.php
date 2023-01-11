<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadVideoRequest;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class TiktokController extends Controller
{
    public function infoVideo(DownloadVideoRequest $request)
    {
        try {
            $data_videos = TiktokWmApi::getVideoDownload(
                method: 'GET',
                tiktok_url: $request->input('tiktok_url')
            );
            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getVideoDownload(
                    method: 'GET',
                    tiktok_url: $request->input('tiktok_url')
                );
            }

            return response([
                'status_code' => 200,
                'message'     => "Success get data info video.",
                'data'        => $data_videos,
                'error'       => false
            ], 200);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response([
            'status_code' => 400,
            'message'     => "Fail get data info video.",
            'data'        => [],
            'error'       => true
        ], 400);
    }
}
