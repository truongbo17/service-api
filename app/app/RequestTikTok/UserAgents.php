<?php

namespace App\RequestTikTok;

enum UserAgents
{
    case DEFAULT;
    case DOWNLOAD;
    case SCRAPE;

    public function get(): string
    {
        return match ($this) {
            UserAgents::DEFAULT => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36",
            UserAgents::DOWNLOAD => "Mozilla/5.0 (X11; Linux x86_64; rv:105.0) Gecko/20100101 Firefox/105.0",
            UserAgents::SCRAPE => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.35",
        };
    }
}
