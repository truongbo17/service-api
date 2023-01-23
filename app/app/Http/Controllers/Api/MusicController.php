<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MusicsRequest;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class MusicController extends Controller
{
    public function get(MusicsRequest $request)
    {
        try {
            $data_videos = TiktokWmApi::getVideosByMusicId(
                method: 'GET',
                music_id: $request->input('music_id'),
                count: $request->input('count', 35),
                cursor: $request->input('cursor', 0),
            );
            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getVideosByMusicId(
                    method: 'GET',
                    music_id: $request->input('music_id'),
                    count: $request->input('count', 35),
                    cursor: $request->input('cursor', 0),
                );
            }

            return response([
                'status_code' => 200,
                'message'     => "Success get data music.",
                'data'        => $data_videos,
                'error'       => false
            ], 200);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response([
            'status_code' => 400,
            'message'     => "Fail get data music.",
            'data'        => [],
            'error'       => true
        ], 400);
    }
}
