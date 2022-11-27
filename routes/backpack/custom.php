<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('video', 'VideoCrudController');
    Route::crud('author', 'AuthorCrudController');
    Route::crud('musics', 'MusicsCrudController');
    Route::crud('trending', 'TrendingCrudController');
    Route::post('trending/create_video_trending', 'TrendingCrudController@makeVideoTrending');
    Route::get('download-video','VideoCrudController@downloadVideo')->name('video.download');
    Route::get('logs','LogController')->name('log-index');
}); // this should be the absolute last line of this file
