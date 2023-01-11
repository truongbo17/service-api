<?php

namespace App\RequestTikTok;

final class Csrf
{
    /**
     * Get csrf token tiktok
     *
     * @param string $url
     * @param array $request_headers
     * @param string $user_agent
     * @return array
     */
    public static function getCsrf(string $url, array $request_headers, string $user_agent): array
    {
        $response = CURL::sendHead(url: $url, req_headers: $request_headers, useragent: $user_agent);

        $headers = $response['headers'];
        $cookies = Cookies::extractCookies($response['data']);

        $csrf_session_id = $cookies['csrf_session_id'] ?? '';
        $csrf_token = isset($headers['x-ware-csrf-token'][0]) ? explode(',', $headers['x-ware-csrf-token'][0])[1] : '';

        return [
            'csrf_session_id' => $csrf_session_id,
            'csrf_token'      => $csrf_token
        ];
    }
}
