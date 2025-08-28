<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $tenant = Tenant::create([
            'name'        => $request['name'],
            'slug'        => $request['slug'],
            'db_database' => $request['database'],
            'db_host'     => $request['db_host'],
            'db_username' => $request['db_username'],
            'db_password' => $request['db_password'],
        ]
        );


        return response()->json([
            'message' => 'Tenant created successfully',
            'data' => $tenant
        ], 201);
    }
}
