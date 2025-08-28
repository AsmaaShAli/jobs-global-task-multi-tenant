<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Job;

class SyncJobToElasticsearch implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $job){ }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->job->searchable();
    }
}
