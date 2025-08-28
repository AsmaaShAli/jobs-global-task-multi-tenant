<?php

namespace App\Http\Controllers;

use App\Jobs\SyncJobToElasticsearch;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function store(Request $request)
    {
        // 1. Save job to database
        $job = Job::create($request->only(['title', 'description']));

        // 2. Queue Elasticsearch sync AFTER response is sent
        dispatch(new SyncJobToElasticsearch($job))->afterResponse();

        // 3. Return API response immediately
        return response()->json([
            'message' => 'Job created successfully',
            'data' => $job
        ], 201);
    }
}
