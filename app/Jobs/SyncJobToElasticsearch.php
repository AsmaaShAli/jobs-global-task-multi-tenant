<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncJobToElasticsearch implements ShouldQueue
{
    use Queueable;

    protected $jobObject;
    /**
     * Create a new job instance.
     */
    public function __construct($job)
    {
        $this->jobObject = $job;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->jobObject->searchable();
        Log::info('job is added to searchable index');

    }
}
