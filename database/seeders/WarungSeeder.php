<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warung;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Support\Str;

class WarungSeeder extends Seeder
{
    public function run()
    {
        // Warung Kantin Atas
        $warungAtas = [
            [
                'name' => 'Nasi Padang',
                'description' => 'Rendang, Ayam Bakar, Sayur Nangka',
                'location' => 'Kantin Atas',
                'rating' => 4.7,
                'distance' => '0.5 km',
                'image' => 'images/nasi-padang.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Ayam Suir',
                'description' => 'Ayam Suir, Es Teh',
                'location' => 'Kantin Atas',
                'rating' => 5.0,
                'distance' => '0.45 km',
                'image' => 'images/ayam-suir.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Warung Indomie',
                'description' => 'Indomie Goreng, Indomie Kuah',
                'location' => 'Kantin Atas',
                'rating' => 4.3,
                'distance' => '0.6 km',
                'image' => 'images/warung-indomie.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Bakso Malang',
                'description' => 'Bakso, Mie Ayam, Pangsit',
                'location' => 'Kantin Atas',
                'rating' => 4.8,
                'distance' => '0.3 km',
                'image' => 'images/bakso.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Sate Madura',
                'description' => 'Sate Ayam, Sate Kambing',
                'location' => 'Kantin Atas',
                'rating' => 4.6,
                'distance' => '0.4 km',
                'image' => 'images/sate.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Gado-gado',
                'description' => 'Gado-gado, Ketoprak, Lontong',
                'location' => 'Kantin Atas',
                'rating' => 4.5,
                'distance' => '0.55 km',
                'image' => 'images/gado-gado.jpg',
                'open_hours' => '08:00 - 17:00',
            ]
        ];

        // Warung Kantin Bawah
        $warungBawah = [
            [
                'name' => 'Hokkian',
                'description' => 'Jus, Siomay, Risol',
                'location' => 'Kantin Bawah',
                'rating' => 4.7,
                'distance' => '0.5 km',
                'image' => 'images/hokkian.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Katsu',
                'description' => 'Chicken Katsu, Steak',
                'location' => 'Kantin Bawah',
                'rating' => 5.0,
                'distance' => '0.45 km',
                'image' => 'images/katsu.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Soto',
                'description' => 'Soto Ayam, Es Jeruk',
                'location' => 'Kantin Bawah',
                'rating' => 4.3,
                'distance' => '0.6 km',
                'image' => 'images/soto.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Warung Korean Food',
                'description' => 'Tteokbokki, Kimchi, Ramyeon',
                'location' => 'Kantin Bawah',
                'rating' => 4.9,
                'distance' => '0.35 km',
                'image' => 'images/korean.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Japanese Corner',
                'description' => 'Sushi, Ramen, Tempura',
                'location' => 'Kantin Bawah',
                'rating' => 4.8,
                'distance' => '0.4 km',
                'image' => 'images/japanese.jpg',
                'open_hours' => '08:00 - 17:00',
            ],
            [
                'name' => 'Chinese Food',
                'description' => 'Capcay, Fuyunghai, Kwetiau',
                'location' => 'Kantin Bawah',
                'rating' => 4.6,
                'distance' => '0.5 km',
                'image' => 'images/chinese.jpg',
                'open_hours' => '08:00 - 17:00',
            ]
        ];

        // Create Warung
        foreach(array_merge($warungAtas, $warungBawah) as $warung) {
            $warung['slug'] = Str::slug($warung['name']);
            $newWarung = Warung::create($warung);

            // Create Menu Categories for each warung
            $categories = ['Makanan Utama', 'Minuman', 'Snack'];
            foreach($categories as $category) {
                $menuCategory = MenuCategory::create([
                    'warung_id' => $newWarung->id,
                    'name' => $category
                ]);

                // Create Menu Items for each category
                if($category === 'Makanan Utama') {
                    MenuItem::create([
                        'menu_category_id' => $menuCategory->id,
                        'name' => 'Menu 1',
                        'description' => 'Deskripsi menu 1',
                        'price' => 25000,
                        'is_available' => true
                    ]);
                    MenuItem::create([
                        'menu_category_id' => $menuCategory->id,
                        'name' => 'Menu 2',
                        'description' => 'Deskripsi menu 2',
                        'price' => 30000,
                        'is_available' => true
                    ]);
                }
            }
        }
    }
}