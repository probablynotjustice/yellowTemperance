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
     // Product::factory()->count(10)->create();

     /*public function definition(): array
            {
                return [
                    'name' => fake()->words(3, true),
                    'description' => fake()->paragraph(),

                    'retail_price' => fake()->numberBetween(100, 1000),
                    'price' => fake()->numberBetween(50, 900),

                    'ticket_cost' => fake()->numberBetween(1, 20),
                    'inventory' => fake()->numberBetween(1, 100),

                    'vendor_id' => 4, // To use with the created Vendor.
                ];
            }
    */
    }
}
