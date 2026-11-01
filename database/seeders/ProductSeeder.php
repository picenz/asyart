<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Asyart Basic Tee',
                'category' => 'T-Shirt',
                'description' => 'Kaos basic dengan desain minimalis.',
                'materials_care' => 'Cotton; cuci dengan warna serupa.',
            ],
            [
                'name' => 'Asyart Oversized Tee',
                'category' => 'Oversized',
                'description' => 'Kaos oversized untuk gaya sehari-hari.',
                'materials_care' => 'Cotton; cuci dengan warna serupa.',
            ],
            [
                'name' => 'Asyart Graphic Tee',
                'category' => 'Graphic Tee',
                'description' => 'Kaos dengan desain grafis khas Asyart.',
                'materials_care' => 'Cotton; hindari menyetrika langsung pada desain.',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                array_merge($item, [
                    'is_active' => true,
                ])
            );
        }
    }
}
