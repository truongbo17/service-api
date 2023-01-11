<?php

namespace App\Console\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use TikScraper\Constants\DownloadMethods;
use TikScraper\Download;
use TruongBo\ProxyRotation\Exception\EmptyHostException;
use TruongBo\ProxyRotation\Rotation;
use TruongBo\ProxyRotation\Servers\Client;
use TruongBo\ProxyRotation\Servers\Host;
use TruongBo\ProxyRotation\Strategy\RoundRobin;
use TruongBo\ProxyRotation\ProxyServer\ProxyCluster;
use TruongBo\ProxyRotation\ProxyServer\ProxyNode;
use GuzzleHttp\HandlerStack;
use TruongBo\ProxyRotation\Middleware\ProxyMiddleware;


class TestApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $api = 'https://www.tikwm.com/api/';
        $tikUrl = 'https://www.tiktok.com/@thuydung_bitrap/video/7175175244393532674?is_copy_url=1&is_from_webapp=v1';
        $postData = [
            'url' => $tikUrl,
            'hd' => 0   //input 1, get HD Video
        ];

        $response = $this->curl_request($api . '?' . http_build_query($postData));
        $obj = json_decode($response);

        if ($obj->code === 0) {
            echo $obj->data->play;    //no watermark
        } else {
            echo $obj->msg;
        }
    }

    private function curl_request($url, $postData = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if ($postData) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_ACCEPTTIMEOUT_MS, 10000);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');

        $response = curl_exec($curl);
        return $response;
    }
}
