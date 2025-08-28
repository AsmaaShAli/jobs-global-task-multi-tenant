<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TenantMigrate extends Command
{
    protected $signature = 'tenants:migrate {--fresh} {--seed}';
    protected $description = 'Run migrations for all tenant databases';

    public function handle()
    {
        $this->info('Starting tenant migrations...');

        foreach (Tenant::all() as $tenant) {
            $this->info("Migrating tenant: {$tenant->name}");

            // Set tenant DB connection dynamically
            config([
                'database.connections.tenant.database' => $tenant->db_database,
                'database.connections.tenant.username' => $tenant->db_username,
                'database.connections.tenant.password' => $tenant->db_password,
                'database.connections.tenant.host'     => $tenant->db_host,
            ]);

            // Decide which migration command to run
            $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

            Artisan::call($command, [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--seed' => $this->option('seed'),
                '--force' => true,
            ]);

            $this->info("✔ Tenant {$tenant->name} migrated");
        }

        $this->info('All tenant migrations completed!');
    }
}
