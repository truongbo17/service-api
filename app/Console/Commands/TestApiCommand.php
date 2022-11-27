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
     * @return int
     * @throws EmptyHostException
     * @throws GuzzleException
     */
    public function handle()
    {

        $downloader = new Download(DownloadMethods::DEFAULT);

        $downloader->url("https://www.tiktok.com/@willsmith/video/7079929224945093934", "example", false);
        die();
        header('Content-Type: application/json');
        $api = new \TikScraper\Api([
            'signer' => [
                'method' => 'remote',
            ]
        ]);
        $item = $api->user('charlidamelio');
        dd($item->feed()->getFull());
        $full = $item->feed()->getFull();
        dd($full);


        $rotation = new Rotation(new RoundRobin(counter: 0));
        $proxy_cluster = new ProxyCluster(
            cluster_name: 'cluster1',
            array_proxy_node: [
                new ProxyNode(name: 'proxy-node1'),
                new ProxyNode(name: 'proxy-node2'),
                new ProxyNode(name: 'proxy-node3'),
                new ProxyNode(name: 'proxy-node4'),
            ]);

        $stack = HandlerStack::create();
//        $stack->push(new ProxyMiddleware(rotation: $rotation, proxy_cluster: $proxy_cluster));

        $host1 = new Host( 'https://faceb2ook.com', 'GET', 2);
        $host2 = new Host( 'https://httpbin.org/ip', 'GET');

        $client = new Client([
            'handler' => $stack,
        ], $host1, $host2);
        dd($client->send()->getStatusCode());

        return CommandAlias::SUCCESS;
    }
}
