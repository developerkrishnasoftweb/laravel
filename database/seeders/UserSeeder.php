<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::factory()
            ->hasRoles(1)
            ->create();
        Role::factory()->create([
            'role' => 'user',
            'description' => 'Customer',
            'status' => 'y'
        ]);
    }
}
