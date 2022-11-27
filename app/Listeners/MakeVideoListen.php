<?php

namespace App\Listeners;

use App\Events\MakeVideoEvent;
use App\Exceptions\InValidDurationVideoException;
use App\Exceptions\InValidTypeVideoException;
use App\Exceptions\MakeVideoException;
use App\Video\MakeVideo;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Throwable;

class MakeVideoListen implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public ?string $queue = 'make_video';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MakeVideoEvent $event
     * @return void
     *
     * @throws InValidTypeVideoException
     * @throws Throwable
     * @throws MakeVideoException
     * @throws InValidDurationVideoException
     */
    public function handle(MakeVideoEvent $event): void
    {
        $make_video = new MakeVideo(type: $event->type_video, duration: $event->duration);
        $make_video->make();
    }

    /**
     * Determine the time at which the listener should time out.
     *
     * @return DateTime
     */
    public function retryUntil(): DateTime
    {
        return now()->addMinutes(10);
    }

    /**
     * Handle a job failure.
     *
     * @param MakeVideoEvent $event
     * @param $exception
     * @return void
     */
    public function failed(MakeVideoEvent $event, $exception): void
    {
        Log::error($exception);
    }
}
