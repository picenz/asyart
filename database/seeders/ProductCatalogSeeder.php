<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Hapus data percobaan beserta data turunannya
            DB::table('product_images')->delete();
            DB::table('product_variants')->delete();
            DB::table('products')->delete();

            $katalog = [
                ['Crop Top Bang Windah', 'Crop Top', 90000],
                ['Regular Tee Bocil Bang Windah', 'Short Sleeve', 85000],
                ['Longsleeve Bang Windah', 'Long Sleeve', 110000],
                ['Crop Top Snoopy', 'Crop Top', 90000],
                ['Crop Top Radio Head', 'Crop Top', 85000],
                ['Crop Top Cake', 'Crop Top', 110000],
                ['Crop Top Blue Star Fish', 'Crop Top', 95000],
                ['Regular Tee Circle Fish', 'Short Sleeve', 85000],
                ['Longsleeve Puffer Fish', 'Long Sleeve', 115000],
                ['Crop Top Snoopy & Bang Windah', 'Crop Top', 75000],
                ['Regular Tee Bocil Sigma', 'Regular Tee', 90000],
                ['Longsleeve Orange Fish', 'Long Sleeve', 80000],
            ];

            foreach ($katalog as [$nama, $kategori, $harga]) {
                Product::create([
                    'name' => $nama,
                    'slug' => Str::slug($nama),
                    'category' => $kategori,
                    'is_active' => true,
                ]);
            }
        });
    }
}
