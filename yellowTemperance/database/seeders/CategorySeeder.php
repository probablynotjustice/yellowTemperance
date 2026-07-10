<?php

namespace Database\Seeders;
Use Illuminate\Support\Str;
use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $categories = [

            [
                'name' => 'Trading Cards',
                'description' => 'Collectible trading cards including sports, gaming, and rare editions.',
            ],
            [
                'name' => 'Electronics',
                'description' => 'Consumer electronics including computers, cameras, and devices.',
            ],
            [
                'name' => 'Luxury',
                'description' => 'High-end watches, jewelry, and premium collectibles.',
            ],
            [
                'name' => 'Collectibles',
                'description' => 'Rare and collectible items from various categories.',
            ],
            [
                'name' => 'Coins & Currency',
                'description' => 'Rare coins, paper currency, and historical monetary items.',
            ],
            [
                'name' => 'Art',
                'description' => 'Paintings, prints, sculptures, and creative works.',
            ],
            [
                'name' => 'Musical Instruments',
                'description' => 'Guitars, keyboards, vintage instruments, and equipment.',
            ],
            [
                'name' => 'Video Games',
                'description' => 'Classic games, consoles, and gaming collectibles.',
            ],
        ];

        foreach ($categories as $category) {

            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);

        }
    }
}

