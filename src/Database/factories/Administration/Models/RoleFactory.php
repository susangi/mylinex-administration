<?php

namespace Database\Factories\Administration\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Administration\Models\Role;
use Administration\Models\Permission;

class RoleFactory extends Factory
{
    protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'guard_name' => 'web'
        ];
    }
}
