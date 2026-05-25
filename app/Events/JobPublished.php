<?php

namespace App\Events;

use App\Models\Job;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Job $job;

    /**
     * Create a new event instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }
}
