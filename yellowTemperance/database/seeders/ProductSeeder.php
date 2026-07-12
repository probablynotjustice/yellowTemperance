<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
    {
        $vendors = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->get();

        $categories = Category::all();
        $productCount = 5;
        foreach ($vendors as $vendor) {

            Product::factory()
                ->count($productCount)
                ->create([
                    'vendor_id' => $vendor->id,
                    'category_id' => $categories->random()->id,
                ]);

        }
    }
}
