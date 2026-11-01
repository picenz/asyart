<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use RuntimeException;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $prices = [
            'crop-top-bang-windah' => 90000,
            'regular-tee-bocil-bang-windah' => 85000,
            'longsleeve-bang-windah' => 110000,
            'crop-top-snoopy' => 90000,
            'crop-top-radio-head' => 85000,
            'crop-top-cake' => 110000,
            'crop-top-blue-star-fish' => 95000,
            'regular-tee-circle-fish' => 85000,
            'longsleeve-puffer-fish' => 115000,
            'crop-top-snoopy-bang-windah' => 75000,
            'regular-tee-bocil-sigma' => 90000,
            'longsleeve-orange-fish' => 80000,
        ];

        DB::transaction(function () use ($prices) {

            // Hapus variant sebelumnya agar tidak duplikat
            DB::table('product_variants')->delete();

            $products = Product::whereIn('slug', array_keys($prices))
                ->get();

            if ($products->count() !== count($prices)) {
                throw new RuntimeException(
                    'Jumlah produk tidak sesuai dengan katalog variant.'
                );
            }

            $colors = [
                'black' => 'BLK',
                'white' => 'WHT',
            ];

            $sizes = ['XS', 'S', 'M', 'L', 'XL'];

            foreach ($products as $product) {
                foreach ($colors as $color => $colorCode) {
                    foreach ($sizes as $size) {

                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color' => $color,
                            'size' => $size,
                            'price' => $prices[$product->slug],
                            'stock' => 10,
                            'sku' => 'ASY-' . $product->id
                                . '-' . $colorCode . '-' . $size,
                            'is_active' => true,
                        ]);

                    }
                }
            }
        });
    }
}
