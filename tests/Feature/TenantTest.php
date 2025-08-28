<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_tenant()
    {
        $response = $this->postJson('/api/tenants', [
            'name' => 'Tenant A',
            'slug' => 'tenant-a',
            'database' => 'tenant_a_db',
            'db_host' => '127.0.0.1',
            'db_username' => 'tenant_user',
            'db_password' => 'secret'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tenants', [
            'slug' => 'tenant-a',
            'db_database' => 'tenant_a_db'
        ]);
    }
}
