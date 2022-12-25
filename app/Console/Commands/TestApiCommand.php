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
    }
}
