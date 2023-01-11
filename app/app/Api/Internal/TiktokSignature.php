<?php

namespace App\Api\Internal;

use GuzzleHttp\Exception\GuzzleException;
use Log;

class TiktokSignature
{
    /**
     * Register signature tiktok url
     *
     * @param string $url
     * @return array
     */
    public static function send(string $url): array
    {
        try {
            $response = app('client')->post(
                config('tiktok.signature_service'),
                [
                    'header' => 'Content-Type: text/plain',
                    'body' => $url
                ]
            );
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            Log::error($exception);
            return [
                "status_code" => "fail",
                "data" => []
            ];
        }
    }
}
