<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use TruongBo\ProxyRotation\Exception\MaxUseNodeException;
use TruongBo\ProxyRotation\Middleware\ProxyMiddleware;
use TruongBo\ProxyRotation\ProxyServer\ProxyCluster;
use TruongBo\ProxyRotation\ProxyServer\ProxyNode;
use TruongBo\ProxyRotation\Rotation;
use TruongBo\ProxyRotation\Strategy\RoundRobin;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var array $list_proxy
     * */
    private array $list_proxy = [
        "http://129.0.21.23"
    ];

    /**
     * Register any application services.
     *
     * @return void
     * @throws MaxUseNodeException
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $config = config('crawl.guzzle');
        if (env('USE_PROXY_ROTATION', false) && count($this->list_proxy) > 0) {
            $config['handler'] = $this->handleProxy();
        }

        $this->app->singleton('client', fn() => new Client($config));
    }

    public function provides()
    {
        return ['client'];
    }

    /**
     * Current use round-robin strategy. If you want change strategy proxy , please read docs :
     * https://github.com/truongbo17/proxy-rotator
     *
     * @throws MaxUseNodeException
     */
    private function handleProxy(): HandlerStack
    {
        $rotation = new Rotation(new RoundRobin(counter: 0));

        $array_proxy = [];
        foreach ($this->list_proxy as $proxy) {
            $array_proxy[] = new ProxyNode(name: $proxy);
        }

        $proxy_cluster = new ProxyCluster(
            cluster_name: 'cluster1',
            array_proxy_node: $array_proxy
        );

        $stack = HandlerStack::create();
        $stack->push(new ProxyMiddleware(rotation: $rotation, proxy_cluster: $proxy_cluster));
        return $stack;
    }
}
