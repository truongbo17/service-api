<?php

namespace App\RequestTikTok;

enum UserAgents
{
    case DEFAULT;
    case DOWNLOAD;

    public function get(): string
    {
        return match ($this) {
            UserAgents::DEFAULT => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36",
            UserAgents::DOWNLOAD => "Mozilla/5.0 (X11; Linux x86_64; rv:105.0) Gecko/20100101 Firefox/105.0",
        };
    }
}
