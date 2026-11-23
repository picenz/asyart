<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ApparelController extends Controller
{
    private function getCatalog()
    {
        return Product::with([
            'variants' => function ($query) {
                $query->where('is_active', true);
            },
            'images' => function ($query) {
                $query->orderByDesc('is_primary')
                    ->orderBy('sort_order');
            }
        ])
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                $blackImage = $product->images->first(function ($image) {
                    return str_contains(
                        strtolower($image->alt_text ?? ''),
                        'black'
                    );
                });

                $whiteImage = $product->images->first(function ($image) {
                    return str_contains(
                        strtolower($image->alt_text ?? ''),
                        'white'
                    );
                });

                $primaryImage = $product->images->first();
                $firstVariant = $product->variants->first();

                return [
                    'id' => $product->id,
                    'nama' => $product->name,
                    'kategori' => $product->category,
                    'harga' => $firstVariant
                        ? 'Rp' . number_format($firstVariant->price, 0, ',', '.')
                        : 'Harga belum tersedia',
                    'gambar_hitam' => $blackImage
                        ? basename($blackImage->image_path)
                        : ($primaryImage ? basename($primaryImage->image_path) : null),
                    'gambar_putih' => $whiteImage
                        ? basename($whiteImage->image_path)
                        : ($primaryImage ? basename($primaryImage->image_path) : null),

                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'id' => $variant->id,
                            'color' => $variant->color,
                            'size' => $variant->size,
                            'price' => (float) $variant->price,
                            'stock' => $variant->stock,
                        ];
                    })->values()->all(),
                ];
            });
    }

    public function index()
    {
        $katalog = $this->getCatalog();

        return view('home', compact('katalog'));
    }

    public function search(Request $request)
    {
        // Batasi panjang query maksimal 100 karakter & bersihkan spasi berlebih
        $rawQuery = (string) $request->input('q', '');
        $keyword = trim(mb_substr($rawQuery, 0, 100));

        $katalog = $this->getCatalog();

        // 1. Cari produk yang cocok secara normal
        $results = $katalog->filter(function ($item) use ($keyword) {
            if ($keyword === '') {
                return false;
            }

            return str_contains(
                strtolower($item['nama']),
                strtolower($keyword)
            ) || str_contains(
                strtolower($item['kategori'] ?? ''),
                strtolower($keyword)
            );
        })->values();

        // 2. Siapkan rekomendasi berdasarkan kemiripan nama (fuzzy / typo tolerance)
        $recommendations = collect();
        $hasSimilar = false;

        if ($results->isEmpty() && $keyword !== '') {
            $keywordLower = strtolower($keyword);

            $recommendations = $katalog->map(function ($item) use ($keywordLower) {
                $name = strtolower($item['nama']);
                $category = strtolower($item['kategori'] ?? '');
                $words = preg_split('/\s+/', $name);

                $bestScore = 0;

                foreach (array_merge([$name, $category], $words) as $word) {
                    if ($word === '') {
                        continue;
                    }

                    similar_text($keywordLower, $word, $similarity);
                    $bestScore = max($bestScore, $similarity);

                    $maxLength = max(strlen($keywordLower), strlen($word));

                    if ($maxLength > 0 && strlen($keywordLower) <= 255 && strlen($word) <= 255) {
                        $distance = levenshtein($keywordLower, $word);
                        $levenshteinScore = (1 - ($distance / $maxLength)) * 100;

                        $bestScore = max($bestScore, $levenshteinScore);
                    }
                }

                $item['similarity'] = $bestScore;

                return $item;
            })
                ->filter(function ($item) {
                    return $item['similarity'] >= 55;
                })
                ->sortByDesc('similarity')
                ->take(4)
                ->values();

            $hasSimilar = $recommendations->isNotEmpty();
        }

        // 3. Kalau tidak ada kemiripan, tampilkan rekomendasi acak
        if ($results->isEmpty() && $recommendations->isEmpty()) {
            $recommendations = $katalog
                ->shuffle()
                ->take(4)
                ->values();
        }

        return view('search', [
            'query' => $keyword,
            'results' => $results,
            'recommendations' => $recommendations,
            'hasSimilar' => $hasSimilar,
            'katalog' => $katalog,
        ]);
    }

    public function cart()
    {
        return view('cart');
    }

    public function show($id)
    {
        $product = $this->getCatalog()
            ->firstWhere('id', (int) $id);

        abort_if(!$product, 404);

        return view('detail-product', compact('product'));
    }
}