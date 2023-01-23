<?php

namespace App\RequestTikTok;

use Illuminate\Support\Str;

final class Query
{
    /**
     * Get query no watermark video
     *
     * @param string $video_id
     * @return array
     */
    public static function queryNoWaterMarkVideo(string $video_id): array
    {
        return [
            'aweme_id'              => $video_id,
            'version_name'          => '26.1.3',
            'version_code'          => 2613,
            'build_number'          => '26.1.3',
            'manifest_version_code' => 2613,
            'update_version_code'   => 2613,
            'openudid'              => Str::random(8),
            'uuid'                  => Str::random(8),
            '_rticket'              => time(),
            'ts'                    => time() * 1000,
            'device_brand'          => 'Google',
            'device_type'           => 'Pixel%204',
            'device_platform'       => 'android',
            'resolution'            => '1080*1920',
            'dpi'                   => 420,
            'os_version'            => 10,
            'os_api'                => 29,
            'carrier_region'        => 'US',
            'sys_region'            => 'US',
            'region'                => 'US',
            'app_name'              => 'trill',
            'app_language'          => 'en',
            'language'              => 'en',
            'timezone_name'         => 'America/New_York',
            'timezone_offset'       => -14400,
            'channel'               => 'googleplay',
            'ac'                    => 'wifi',
            'mcc_mnc'               => 310260,
            'is_my_cn'              => 0,
            'aid'                   => 1180,
            'ssmix'                 => 'a',
            'as'                    => 'a1qwert123',
            'cp'                    => 'cbfhckdckkde1'
        ];
    }

    /**
     * Query basic tiktok
     *
     * @param array $query
     * @return array
     */
    public static function queryBasicTiktok(array $query = []): array
    {
        return array_merge($query, [
            "aid"              => 1988,
            "app_language"     => 'en',
            "app_name"         => "tiktok_web",
            "browser_language" => "en-us",
            "browser_name"     => "Mozilla",
            "browser_online"   => true,
            "browser_platform" => "iPhone",
            "browser_version"  => urlencode(UserAgents::SCRAPE->get()),
            "channel"          => "tiktok_web",
            "cookie_enabled"   => true,
            "device_platform"  => "web_mobile",
            "focus_state"      => true,
            "history_len"      => rand(1, 5),
            "is_fullscreen"    => false,
            "is_page_visible"  => true,
            "os"               => "ios",
            "priority_region"  => "",
            "referer"          => "",
            "region"           => "us",
            "screen_width"     => 1920,
            "screen_height"    => 1080,
            "timezone_name"    => "America/Chicago",
            "webcast_language" => "en"
        ]);
    }
}
