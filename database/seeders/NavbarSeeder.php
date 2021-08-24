<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Navbar;

class NavbarSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Navbar::factory()->create([
            'title' => 'Dashboard',
            'url' => '/admin/dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'parent_nav_id' => 0,
            'nav_type' => 'menu',
            'position' => 1,
            'status' => 'y',
        ]);
        Navbar::factory()->create([
            'title' => 'Banners',
            'url' => '/admin/banner',
            'icon' => 'fas fa-th',
            'parent_nav_id' => 0,
            'nav_type' => 'menu',
            'position' => 2,
            'status' => 'y',
        ]);
        Navbar::factory()->create([
            'title' => 'Users',
            'url' => '#',
            'icon' => 'fas fa-users',
            'parent_nav_id' => 0,
            'nav_type' => 'group',
            'position' => 3,
            'status' => 'y',
        ]);
        Navbar::factory()->create([
            'title' => 'Users',
            'url' => '/admin/user',
            'icon' => 'fas fa-users',
            'parent_nav_id' => 3,
            'nav_type' => 'menu',
            'position' => 1,
            'status' => 'y',
        ]);
        Navbar::factory()->create([
            'title' => 'Roles',
            'url' => '/admin/role',
            'icon' => 'fas fa-users-cog',
            'parent_nav_id' => 3,
            'nav_type' => 'menu',
            'position' => 1,
            'status' => 'y',
        ]);
    }
}
