<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use TruongBo\ProxyRotation\Middleware\ProxyMiddleware;
use TruongBo\ProxyRotation\ProxyServer\ProxyCluster;
use TruongBo\ProxyRotation\ProxyServer\ProxyNode;
use TruongBo\ProxyRotation\Rotation;
use TruongBo\ProxyRotation\Strategy\RoundRobin;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $config = config('crawl.guzzle');
        if (env('USE_PROXY_ROTATION')) {
            $config['handler'] = $this->handleProxy();
        }

        $this->app->singleton('client', fn() => new Client($config));
    }

    private function handleProxy(): HandlerStack
    {
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
        $stack->push(new ProxyMiddleware(rotation: $rotation, proxy_cluster: $proxy_cluster));
        return $stack;
    }

    public function provides()
    {
        return ['client'];
    }
}
