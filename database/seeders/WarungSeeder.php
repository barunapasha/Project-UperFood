<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class WarungSeeder extends Seeder
{
    public function run()
    {
        // Create Warung
        $warung = Warung::create([
            'name' => 'Warung Padang',
            'slug' => 'warung-padang',
            'description' => 'Menyediakan berbagai masakan Padang',
            'location' => 'Kantin Atas',
            'image' => 'images/warung-padang.jpg',
            'open_hours' => '08:00 - 17:00',
            'distance' => '0.5 km',
            'rating' => 4.5
        ]);

        // Create Menu Category
        $category = MenuCategory::create([
            'warung_id' => $warung->id,
            'name' => 'Makanan Utama'
        ]);

        // Create Menu Items
        MenuItem::create([
            'menu_category_id' => $category->id,
            'name' => 'Rendang',
            'description' => 'Rendang daging sapi',
            'price' => 25000,
            'image' => 'images/rendang.jpg',
            'is_available' => true
        ]);
    }
}