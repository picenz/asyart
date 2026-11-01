<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use RuntimeException;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'crop-top-bang-windah' => [
                'windahcrop-htm.jpg',
                'windahcrop-pth.jpg',
            ],
            'regular-tee-bocil-bang-windah' => [
                'windahreg-htm.jpg',
                'windahreg-pth.jpg',
            ],
            'longsleeve-bang-windah' => [
                'windahpnjg-htm.jpg',
                'windahpnjg-pth.jpg',
            ],
            'crop-top-snoopy' => [
                'snpcrop-htm.jpg',
                'snpcrop-pth.jpg',
            ],
            'crop-top-radio-head' => [
                'radiocrop-htm.jpg',
                'radiocrop-pth.jpg',
            ],
            'crop-top-cake' => [
                'cakecrop-htm.jpg',
                'cakecrop-pth.jpg',
            ],
            'crop-top-blue-star-fish' => [
                'ikanbirucrop-htm.jpg',
                'ikanbirucrop-pth.jpg',
            ],
            'regular-tee-circle-fish' => [
                'ikanbulatreg-htm.jpg',
                'ikanbulatreg-pth.jpg',
            ],
            'longsleeve-puffer-fish' => [
                'ikanbuntalpnjg-htm.jpg',
                'ikanbuntalpnjg-pth.jpg',
            ],
            'crop-top-snoopy-bang-windah' => [
                'npcrop-htm.jpg',
                'npcrop-pth.jpg',
            ],
            'regular-tee-bocil-sigma' => [
                'bclreg-htm.jpg',
                'bclreg-pth.jpg',
            ],
            'longsleeve-orange-fish' => [
                'ikanorenpnjg-htm.jpg',
                'ikanorenpnjg-pth.jpg',
            ],
        ];

        DB::transaction(function () use ($images) {

            DB::table('product_images')->delete();

            foreach ($images as $slug => $files) {

                $product = Product::where('slug', $slug)->first();

                if (!$product) {
                    throw new RuntimeException(
                        "Produk dengan slug {$slug} tidak ditemukan."
                    );
                }

                foreach ($files as $index => $filename) {

                    $path = 'images/' . $filename;

                    if (!file_exists(public_path($path))) {
                        throw new RuntimeException(
                            "File gambar tidak ditemukan: {$path}"
                        );
                    }

                    DB::table('product_images')->insert([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $product->name . ' - ' .
                            ($index === 0 ? 'Black' : 'White'),
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}
