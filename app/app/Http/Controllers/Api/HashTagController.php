<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HashTagRequest;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class HashTagController extends Controller
{
    public function get(HashTagRequest $request)
    {
        try {
            $data_videos = TiktokWmApi::getVideoByHashTag(
                method: 'GET',
                challenge_name: $request->input('challenge_name'),
                count: $request->input('count', 35),
                cursor: $request->input('cursor', 0)
            );
dump($data_videos);
//            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getVideoByHashTag(
                    method: 'GET',
                    challenge_name: $request->input('challenge_name'),
                    count: $request->input('count', 35),
                    cursor: $request->input('cursor', 0)
                );
//            }
dd($data_videos);
            return response([
                'status_code' => 200,
                'message'     => "Success get data hashtag.",
                'data'        => $data_videos,
                'error'       => false
            ], 200);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response([
            'status_code' => 400,
            'message'     => "Fail get data hashtag.",
            'data'        => [],
            'error'       => true
        ], 400);
    }
}
