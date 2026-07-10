<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $vendorRole = Role::where('name', 'vendor')->firstOrFail();
        $customerRole = Role::where('name', 'customer')->firstOrFail();


        $adminCount = 1;
        $vendorCount = 5;
        $customerCount = 20;

    //Administrator
        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin2@example.com',
        ]);

        $admin->roles()->attach($adminRole);


    //Vendors
        User::factory()
            ->count($vendorCount)
            ->create()
            ->each(function (User $user) use ($vendorRole) {

                $user->roles()->attach($vendorRole);

            });


    //Customers
        User::factory()
            ->count($customerCount)
            ->create()
            ->each(function (User $user) use ($customerRole) {

                $user->roles()->attach($customerRole);

            });
    }
}

