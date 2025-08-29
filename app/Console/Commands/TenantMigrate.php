<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    protected $signature = 'tenants:migrate {--fresh} {--seed}';
    protected $description = 'Run migrations for all tenant databases';

    public function handle()
    {
        $this->info('Starting tenant migrations...');

        foreach (Tenant::all() as $tenant) {
            $this->info("Migrating tenant: {$tenant->name}");

            $this->createDatabaseIfNotExists($tenant);

            // Set tenant DB connection dynamically
            config([
                'database.connections.tenant' => [
                    'driver'   => 'mysql',
                    'host'     => $tenant->db_host,
                    'port'     => env('DB_PORT', 3306),
                    'database' => $tenant->db_database,
                    'username' => $tenant->db_username,
                    'password' => $tenant->db_password,
                ]
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

    private function createDatabaseIfNotExists($tenant): void
    {
        // Use root connection from .env
        DB::purge('mysql_root');
        config(['database.connections.mysql_root' => [
            'driver'   => 'mysql',
            'host'     => $tenant->db_host,
            'port'     => env('DB_PORT', 3306),
            'database' => 'laravel', // we need a default db so that we can create another one, this is the main db for the app.
            'username' => env('TENANT_DB_USERNAME', 'root'),
            'password' => env('TENANT_DB_PASSWORD', ''),
        ]]);

        $root = DB::connection('mysql_root');

        $dbName = $tenant->db_database;
        $dbUser = $tenant->db_username;
        $dbPass = $tenant->db_password;

        // Create DB if missing
        $root->statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        // Create user and grant privileges
        $root->statement("CREATE USER IF NOT EXISTS '$dbUser'@'%' IDENTIFIED BY '$dbPass'");
        $root->statement("GRANT ALL PRIVILEGES ON `$dbName`.* TO '$dbUser'@'%'");
        $root->statement("FLUSH PRIVILEGES");

        $this->info("   📂 Database {$dbName} ready for tenant {$tenant->slug}");
    }
}
