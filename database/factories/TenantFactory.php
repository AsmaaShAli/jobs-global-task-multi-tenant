<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->company,
            'slug'        => $this->faker->unique()->slug,
            'db_database' => $this->faker->unique()->word . '_db',
            'db_host'     => '127.0.0.1',
            'db_username' => 'root',
            'db_password' => 'password',
        ];
    }
}
