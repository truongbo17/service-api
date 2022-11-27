<?php

namespace App\RequestTikTok;

final class Headers
{
    const DEFAULT_API_HEADERS = [
        "authority"       => "m.tiktok.com",
        "method"          => "GET",
        "scheme"          => "https",
        "accept"          => " application/json, text/plain, */*",
        "accept-encoding" => " gzip, deflate, br",
        "accept-language" => " en-US,en;q=0.9",
        "sec-fetch-dest"  => " empty",
        "sec-fetch-mode"  => " cors",
        "sec-fetch-site"  => " same-site",
        "sec-gpc"         => " 1"
    ];
}
