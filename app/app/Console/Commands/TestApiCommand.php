<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TikScraper\Api;
use TiktokApiNature;


class TestApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api {--test}';

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
        if ($this->option('test')) {
            $api = new Api(['signer' => ['method' => 'remote', 'url' => 'http://localhost:8080/signature']]);
            $item = $api->hashtag('funny');
            $full = $item->feed(20)->getFull();
            dd($full->toJson());
        } else {
            $data = TiktokApiNature::getVideoByHashTag(
                method: 'GET',
                challenge_name: 'funny',
                count: 30,
                cursor: 20
            );
            dd($data);
        }
    }
}
