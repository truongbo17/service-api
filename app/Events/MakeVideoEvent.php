<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MakeVideoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $duration;
    public string $type_video;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $type_video, int $duration)
    {
        $this->duration = $duration;
        $this->type_video = $type_video;
    }
}
