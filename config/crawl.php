<?php

return [
    'guzzle' => [
        'timeout' => env('BROWSER_TIMEOUT', 30),
        'verify' => false,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        ],
        'http_errors' => false,
        'allow_redirects' => [
            'track_redirects' => true
        ],
        'cookies' => true
    ],
    'disk_save_video_trending' => 'video_trending',
    'disk_save_video_hashtag' => 'video_hashtag',
    'disk_save_video_user' => 'video_user',
    'disk_save_video_sound' => 'video_sound',
    'disk_video' => 'videos',
];
