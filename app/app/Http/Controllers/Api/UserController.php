<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use Exception;
use Log;
use TiktokApiNature;
use TiktokWmApi;

class UserController extends Controller
{
    public function get(AuthorRequest $request)
    {
        try {
            $data_videos = TiktokWmApi::getVideosByUser(
                method: 'GET',
                unique_id: $request->input('unique_id'),
                count: $request->input('count', 35),
                cursor: $request->input('cursor', 0),
            );
            if (count($data_videos) < 1) {
                $data_videos = TiktokApiNature::getVideosByUser(
                    method: 'GET',
                    unique_id: $request->input('unique_id'),
                    count: $request->input('count', 35),
                    cursor: $request->input('cursor', 0),
                );
            }

            return response([
                'status_code' => 200,
                'message'     => "Success get data user.",
                'data'        => $data_videos,
                'error'       => false
            ], 200);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response([
            'status_code' => 400,
            'message'     => "Fail get data user.",
            'data'        => [],
            'error'       => true
        ], 400);
    }
}
