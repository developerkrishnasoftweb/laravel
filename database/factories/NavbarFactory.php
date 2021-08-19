<?php

namespace Database\Factories;

use App\Models\Navbar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NavbarFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Navbar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'title' => 'Dashboard',
            'url' => '/admin/dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'parent_nav_id' => 0,
            'nav_type' => 'menu',
            'position' => 1,
            'status' => 'y',
        ];
    }
}
