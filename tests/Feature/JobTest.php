<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Database\Factories\TenantFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Job;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SyncJobToElasticsearch;

class JobTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // First create a tenant in central DB
        $this->tenant = Tenant::create([
            'name' => 'Tenant One',
            'slug' => 'tenant-one',
            'db_database' => 'tenant_one_db',
            'db_host' => '127.0.0.1',
            'db_username' => 'root',
            'db_password' => 'password',
        ]);

        // 👇 In real life you'd run migrations on tenant DB here
        // For tests, we can just pretend it's migrated already
        //DB::statement('CREATE TABLE jobs (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT)');
    }

    /** @test */
    public function it_creates_a_job_and_dispatches_to_queue()
    {
        Queue::fake();

        $response = $this->postJson('/api/jobs', [
            'title' => 'Backend Developer',
            'description' => 'Work on APIs',
        ], [
            'X-Tenant-ID' => $this->tenant->slug,
        ]);

        $response->assertStatus(201);

        Queue::assertPushed(SyncJobToElasticsearch::class);
    }
}
