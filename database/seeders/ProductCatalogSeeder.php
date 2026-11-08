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
            // // Hapus data percobaan beserta data turunannya
            // DB::table('product_images')->delete();
            // DB::table('product_variants')->delete();
            // DB::table('products')->delete();

            $katalog = [
                ['Baby Tee Bang Windah', 'Baby Tee', 90000],
                ['Baby Tee Bocil Bang Windah', 'Baby Tee', 85000],
                ['Longsleeve Bang Windah', 'Long Sleeve', 110000],
                ['Baby Tee Snoopy', 'Baby Tee', 90000],
                ['Baby Tee Radio Head', 'Baby Tee', 85000],
                ['Baby Tee Cake', 'Baby Tee', 110000],
                ['Baby Tee Blue Star Fish', 'Baby Tee', 95000],
                ['Baby Tee Circle Fish', 'Baby Tee', 85000],
                ['Longsleeve Puffer Fish', 'Long Sleeve', 115000],
                ['Baby Tee Snoopy & Bang Windah', 'Baby Tee', 75000],
                ['Baby Tee Bocil Sigma', 'Baby Tee', 90000],
                ['Longsleeve Orange Fish', 'Long Sleeve', 80000],
            ];

            foreach ($katalog as [$nama, $kategori, $harga]) {
                $namaLama = str_replace('Baby Tee ', 'Crop Top ', $nama);

                $product = Product::where('slug', Str::slug($namaLama))->first();

                // Kalau bukan produk Crop Top lama, cari berdasarkan nama sekarang
                if (!$product) {
                    $product = Product::where('slug', Str::slug($nama))->first();
                }

                if (!$product) {
                    continue;
                }

                $product->update([
                    'name' => $nama,
                    'slug' => Str::slug($nama),
                    'category' => $kategori,
                    'is_active' => true,
                ]);
            }
        });
    }
}
