<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrendingRequest;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class TrendingController extends Controller
{
    public function get(TrendingRequest $request)
    {
        try {
            $data_videos = TiktokWmApi::getTrendingVideo(
                method: 'GET',
                region: 'VN',
                count: $request->input('count', 35)
            );
            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getTrending(
                    method: 'GET',
                    count: $request->input('count', 35)
                );
            }

            return response([
                'status_code' => 200,
                'message'     => "Success get data trending.",
                'data'        => $data_videos,
                'error'       => true
            ], 200);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response([
            'status_code' => 400,
            'message'     => "Fail get data trending.",
            'data'        => [],
            'error'       => true
        ], 400);
    }
}
