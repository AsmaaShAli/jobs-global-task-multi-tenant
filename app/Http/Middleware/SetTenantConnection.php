<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('testing')) {
            config(['database.connections.tenant.driver' => 'sqlite']);
            config(['database.connections.tenant.database' => ':memory:']);
        }

        $tenantSlug = $request->header('X-Tenant-ID');

        if (!$tenantSlug) {
            return response()->json([
                'error' => 'Tenant identifier (X-Tenant-ID) missing'
            ], 400);
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();


        if (! $tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $this->adjustConnections($tenant);

        DB::setDefaultConnection('tenant');
        app()->instance(Tenant::class, $tenant);
        return $next($request);
    }

    private function adjustConnections($tenant)
    {
        //  If running tests, force SQLite in-memory connection
        if (app()->environment('testing')) {
            config([
                'database.connections.tenant' => [
                    'driver'   => 'sqlite',
                    'database' => ':memory:',
                    'prefix'   => '',
                ],
            ]);

            // run migrations for tenant schema in memory
            \Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant', // put tenant-specific migrations here
                '--force' => true,
            ]);
        } else {
            // normal MySQL tenant DB config
            config([
                'database.connections.tenant' => [
                    'driver'   => 'mysql',
                    'host'     => $tenant->db_host,
                    'port'     => env('TENANT_DB_PORT', '3306'),
                    'database' => $tenant->db_database,
                    'username' => $tenant->db_username,
                    'password' => $tenant->db_password,
                    'charset'  => 'utf8mb4',
                    'collation'=> 'utf8mb4_unicode_ci',
                    'prefix'   => '',
                    'strict'   => true,
                    'engine'   => null,
                ],
            ]);
        }
    }
}
