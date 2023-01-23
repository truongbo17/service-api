<?php

namespace App\RequestTikTok;

use App\Api\Internal\TiktokSignature;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class Requests
{
    /**
     * Send request
     *
     * @param ClientInterface $client
     * @param string $endpoint
     * @param string $path
     * @param string $device_id
     * @param string $method
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function send(ClientInterface $client, string $endpoint, string $path, string $device_id, string $method = 'GET'): ResponseInterface
    {
        $signature = TiktokSignature::send(url: $endpoint);
        if ($signature['status'] ?? "" == "ok") {
            $url = $signature['data']['signed_url'];
            $user_agent = $signature['data']['navigator']['user_agent'];
            $headers = array_merge(Headers::DEFAULT_API_HEADERS, [
                "Path" => "/$path"
            ]);

            $extra = Csrf::getCsrf(
                url: $url,
                request_headers: config('tiktok.request_headers_tiktok'),
                user_agent: $user_agent
            );

            $headers['x-secsdk-csrf-token'] = $extra['csrf_token'];
            $headers['User-Agent'] = $user_agent;
            $cookies = Cookies::getCookies(device_id: $device_id, csrf_session_id: $extra['csrf_session_id']);
            $cookies = Cookies::convertStringToArray(cookies: $cookies);
            $cookies_jar = CookieJar::fromArray($cookies, 'tiktok.com');

            return $client->request($method, $url, [
                'headers' => $headers,
                'cookies' => $cookies_jar,
            ]);
        }

        return new Response(500);
    }
}
