<?php

use Illuminate\Support\Facades\Route;
use TikScraper\Constants\DownloadMethods;
use TikScraper\Download;
use TikScraper\Helpers\Algorithm;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('download', function () {
    $downloader = new Download(DownloadMethods::DEFAULT);
    $downloader->url("https://www.tiktok.com/@willsmith/video/7079929224945093934", "example", false);
});

Route::get('video', function () {
//    $b = TiktokWmApi::getVideoByHashTag('get', 'funny', 10, 1);
//    dump($b);

    $a = TiktokApiNature::getVideosByUser('GET','tagnr_21');
    dd($a);
});

Route::get('tiktok', function () {
    $api = new \TikScraper\Api([
        'signer' => [
            'method' => 'remote',
            'url'    => 'http://localhost:8080/signature'
        ]
    ]);
    $item = $api->video('7157970529322798362');
    $full = $item->feed()->getFull();
    dd($full);
});
