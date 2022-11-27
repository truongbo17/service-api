<?php

namespace App\Console\Commands;

use App\Enums\TypeVideoEnum;
use Illuminate\Console\Command;
use TiktokWmApi;

class MakeVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:video';

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
     */
    public function handle()
    {
        dd(TiktokWmApi::getVideosByUser(method: 'GET', unique_id: 'chocochavani_', count: 1, cursor: 1668305764000));
//        dd(TiktokWmApi::getVideosByUser(method: 'GET', unique_id: 'chocochavani_', count: 1, cursor: 1));

        $make_video = new \App\Video\MakeVideo(TypeVideoEnum::USER,  180, true, true, [
            'unique_user' => 'chocochavani_'
        ]);

        dd($make_video->make());
        return Command::SUCCESS;
    }
}
