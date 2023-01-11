<?php

namespace App\RequestTikTok;

final class CURL
{
    public static function sendHead(string $url, array $req_headers = [], string $useragent = ''): array
    {
        if (!$useragent) {
            $useragent = UserAgents::DEFAULT->get();
        }
        $headers = [];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => $useragent,
            CURLOPT_ENCODING       => 'utf-8',
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_HTTPHEADER     => $req_headers
        ]);

        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) return $len;
            $headers[strtolower(trim($header[0]))][] = trim($header[1]);
            return $len;
        });

        $data = curl_exec($ch);
        curl_close($ch);
        return [
            'data'    => $data,
            'headers' => $headers
        ];
    }

    public static function sendHTML(string $url, array $query = []): string
    {
        $ch = curl_init();
        if (!empty($query)) $url .= '?' . http_build_query($query);
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => UserAgents::DEFAULT->get(),
            CURLOPT_ENCODING       => 'utf-8',
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
