<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_id')->unique()->index();
            $table->string('music_id')->index()->nullable();
            $table->string('author_id')->index()->nullable();
            $table->string('region', 5)->nullable();
            $table->string('title', 500);
            $table->string('hash_title')->index();
            $table->string('storage_file')->nullable();
            $table->integer('duration')->default(0);
            $table->unsignedBigInteger('play_count')->default(0);
            $table->unsignedBigInteger('digg_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->integer('width')->default(0)->index();
            $table->integer('height')->default(0)->index();
            $table->string('frame', 20)->default("")->index();
            $table->timestamp('create_time');
            $table->integer('is_trending')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
