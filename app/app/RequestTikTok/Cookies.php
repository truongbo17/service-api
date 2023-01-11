<?php

namespace App\RequestTikTok;

use Exception;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use Log;
use Str;

final class Cookies
{
    public static function getCookies(string $device_id, string $csrf_session_id): string
    {
        $cookies = '';
        $cookies_array = [
            'tt_webid'        => $device_id,
            'tt_webid_v2'     => $device_id,
            "csrf_session_id" => $csrf_session_id,
            "tt_csrf_token"   => Str::random(16)
        ];

        foreach ($cookies_array as $key => $value) {
            $cookies .= "{$key}={$value};";
        }

        return $cookies;
    }

    public static function extractCookies(string $data): array
    {
        $cookies = [];
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $data, $matches);
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        return $cookies;
    }

    public static function convertStringToArray(string $cookies): array
    {
        try {
            $cookies = array_filter(explode(';', $cookies));
            $result = [];
            foreach ($cookies as $cookie) {
                $cookie = explode('=', $cookie);
                $result[$cookie[0]] = $cookie[1];
            }
            return $result;
        } catch (Exception $exception) {
            Log::error($exception);
            return [];
        }
    }

    public static function beautifullCookieArray(array $cookies)
    {

    }

    /**
     * Get cookie from config
     * @param array $cookies_config
     * @param string $cookie_domain
     * @return CookieJarInterface
     */
    public static function generateCookieGuzzleFromConfig(array $cookies_config, string $cookie_domain): CookieJarInterface
    {
        $cookie_new = [];
        foreach ($cookies_config as $cookie) {
            $cookie_new[$cookie['name']] = $cookie['value'];
        }

        return CookieJar::fromArray($cookie_new, $cookie_domain);
    }
}
