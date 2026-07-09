<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Role;
class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorRole = Role::where('name', 'vendor')->first();

        for ($i = 1; $i <= 5; $i++) {

            $vendor = User::factory()->create([
                'name' => "Vendor {$i}",
                'email' => "vendor{$i}@example.com",
            ]);

            $vendor->roles()->attach($vendorRole->id);
        }
            }
}
