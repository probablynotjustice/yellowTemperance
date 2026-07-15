<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
Use App\Models\Wallet;
class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        foreach ($customers as $customer) {
            Wallet::factory()->create([
                'user_id' => $customer->id,
            ]);
        }
    }
}
