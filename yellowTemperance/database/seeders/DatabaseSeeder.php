<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

      $this->call([
        RoleSeeder::class,
    ]);
    // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);
        User::factory()->create([
            'name' => 'User-customer',
            'email' => 'cust@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,


        ]);
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
      Product::factory()->count(10)->create();

    }
}
