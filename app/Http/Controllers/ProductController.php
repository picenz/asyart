<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('images')
            ->with('variants')
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    // Menampilkan detail satu produk
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'images',
                'variants' => function ($query) {
                    $query->where('is_active', true)
                        ->where('stock', '>', 0);
                },
            ])
            ->firstOrFail();

        return view('products.show', compact('product'));
    }

    public function home(Request $request)
{
    $query = Product::where('is_active', true)
        ->with(['images', 'variants']);

    // Fitur pencarian produk
    if ($request->filled('q')) {
        $search = $request->q;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
               ->orWhere('category', 'like', "%{$search}%");
        });
    }

    $products = $query->latest()->get();

    return view('home', compact('products'));
}

public function apiIndex()
{
    $products = Product::where('is_active', true)
        ->with(['images', 'variants'])
        ->paginate(12);

    return response()->json($products);
}

public function apiShow(int $id)
{
    $product = Product::where('is_active', true)
        ->with(['images', 'variants'])
        ->findOrFail($id);

    return response()->json($product);
}



public function apiStore(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:products,slug',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:255',
        'materials_care' => 'nullable|string',
        'is_active' => 'sometimes|boolean',

        'images' => 'sometimes|array|max:10',
        'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        'images.*.alt_text' => 'nullable|string|max:255',

        'variants' => 'required|array|min:1',
        'variants.*.color' => 'required|string|max:255',
        'variants.*.size' => 'required|string|max:50',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.stock' => 'sometimes|integer|min:0',
        'variants.*.sku' => 'nullable|string|max:255|distinct|unique:product_variants,sku',
        'variants.*.is_active' => 'sometimes|boolean',
    ]);
$slug = !empty($validated['slug'])
    ? $validated['slug']
    : Str::slug($validated['name']);

    $slugQuery = Product::where('slug', $slug);

    if ($slugQuery->exists()) {
        throw ValidationException::withMessages([
            'slug' => ['Slug produk sudah digunakan.'],
        ]);
    }

    $uploadedFiles = [];

    try {
        $product = DB::transaction(function () use (
            $request,
            $validated,
            $slug,
            &$uploadedFiles
        ) {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
                'materials_care' => $validated['materials_care'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach ($validated['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => $variant['color'],
                    'size' => $variant['size'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'] ?? 0,
                    'sku' => $variant['sku'] ?? null,
                    'is_active' => $variant['is_active'] ?? true,
                ]);
            }

            foreach ($request->file('images', []) as $index => $image) {
                $path = $image->store('products', 'public');

                if (!$path) {
                    throw new \RuntimeException(
                        'Gagal menyimpan gambar produk.'
                    );
                }

                $uploadedFiles[] = $path;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }

            return $product->load(['images', 'variants']);
        });
    } catch (\Throwable $e) {
        foreach ($uploadedFiles as $path) {
            Storage::disk('public')->delete($path);
        }

        throw $e;
    }

    return response()->json([
        'message' => 'Produk, gambar, dan varian berhasil ditambahkan.',
        'data' => $product,
    ], 201);
}

    // UPDATE: edit produk

public function apiUpdate(Request $request, int $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validate([
        // Data utama produk
        'name' => 'sometimes|required|string|max:255',
        'slug' => [
            'sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique('products', 'slug')->ignore($product->id),
        ],
        'description' => 'sometimes|nullable|string',
        'category' => 'sometimes|nullable|string|max:255',
        'materials_care' => 'sometimes|nullable|string',
        'is_active' => 'sometimes|boolean',

        // Tambah gambar baru
        'images' => 'sometimes|array|max:10',
        'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',

        // Hapus gambar berdasarkan ID
        'delete_image_ids' => 'sometimes|array',
        'delete_image_ids.*' => 'integer|distinct',

        // Edit varian berdasarkan ID
        'variants' => 'sometimes|array',
        'variants.*.id' => 'required|integer',
        'variants.*.color' => 'sometimes|required|string|max:255',
        'variants.*.size' => 'sometimes|required|string|max:50',
        'variants.*.price' => 'sometimes|required|numeric|min:0',
        'variants.*.stock' => 'sometimes|required|integer|min:0',
        'variants.*.sku' => 'sometimes|nullable|string|max:255',
        'variants.*.is_active' => 'sometimes|boolean',

        // Tambah varian baru
        'new_variants' => 'sometimes|array',
        'new_variants.*.color' => 'required|string|max:255',
        'new_variants.*.size' => 'required|string|max:50',
        'new_variants.*.price' => 'required|numeric|min:0',
        'new_variants.*.stock' => 'sometimes|integer|min:0',
        'new_variants.*.sku' => 'nullable|string|max:255',
        'new_variants.*.is_active' => 'sometimes|boolean',

        // Hapus varian berdasarkan ID
        'delete_variant_ids' => 'sometimes|array',
        'delete_variant_ids.*' => 'integer|distinct',
    ]);

    // Pastikan ID gambar dan varian memang milik produk ini
    foreach ($validated['delete_image_ids'] ?? [] as $imageId) {
        abort_unless(
            $product->images()->whereKey($imageId)->exists(),
            422,
            'ID gambar tidak valid untuk produk ini.'
        );
    }

    foreach ($validated['variants'] ?? [] as $variant) {
        abort_unless(
            $product->variants()->whereKey($variant['id'])->exists(),
            422,
            'ID varian tidak valid untuk produk ini.'
        );
    }

    foreach ($validated['delete_variant_ids'] ?? [] as $variantId) {
        abort_unless(
            $product->variants()->whereKey($variantId)->exists(),
            422,
            'ID varian tidak valid untuk produk ini.'
        );
    }

    // Cek SKU unik, termasuk saat mengedit varian lama
    foreach ($validated['variants'] ?? [] as $variant) {
        if (array_key_exists('sku', $variant) && $variant['sku'] !== null) {
            $exists = ProductVariant::where('sku', $variant['sku'])
                ->where('product_id', '!=', $product->id)
                ->where('id', '!=', $variant['id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'SKU sudah digunakan.',
                ], 422);
            }
        }
    }

    $uploadedFiles = [];
    $deletedPaths = [];

    try {
        DB::transaction(function () use (
            $request,
            $product,
            $validated,
            &$uploadedFiles,
            &$deletedPaths
        ) {
            // Update data utama; field yang tidak dikirim tidak berubah
            $product->update(
                collect($validated)->only([
                    'name',
                    'slug',
                    'description',
                    'category',
                    'materials_care',
                    'is_active',
                ])->all()
            );

            // Edit varian lama
            foreach ($validated['variants'] ?? [] as $variant) {
                $variantModel = $product->variants()
                    ->whereKey($variant['id'])
                    ->firstOrFail();

                $variantModel->update(
                    collect($variant)->except('id')->all()
                );
            }

            // Tambah varian baru
            foreach ($validated['new_variants'] ?? [] as $variant) {
                $product->variants()->create($variant);
            }

            // Hapus varian yang dipilih
            foreach ($validated['delete_variant_ids'] ?? [] as $variantId) {
                $product->variants()->whereKey($variantId)->delete();
            }

            // Tambah gambar baru
            foreach ($request->file('images', []) as $index => $image) {
                $path = $image->store('products', 'public');

                if (!$path) {
                    throw new \RuntimeException(
                        'Gagal menyimpan gambar produk.'
                    );
                }

                $uploadedFiles[] = $path;

                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'is_primary' => !$product->images()->exists()
                        && $index === 0,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }

            // Hapus gambar yang dipilih
            foreach ($validated['delete_image_ids'] ?? [] as $imageId) {
                $image = $product->images()->whereKey($imageId)->firstOrFail();

                $deletedPaths[] = $image->image_path;
                $image->delete();
            }
        });
    } catch (\Throwable $e) {
        foreach ($uploadedFiles as $path) {
            Storage::disk('public')->delete($path);
        }

        throw $e;
    }

    // Hapus file fisik setelah transaksi database berhasil
    foreach ($deletedPaths as $path) {
        Storage::disk('public')->delete($path);
    }

    return response()->json([
        'message' => 'Produk berhasil diperbarui.',
        'data' => $product->fresh()->load(['images', 'variants']),
    ]);
}


    // DELETE: nonaktifkan produk
    public function apiDestroy(int $id)
    {
        $product = Product::findOrFail($id);

        // Lebih aman daripada menghapus produk yang
        // mungkin sudah memiliki varian atau gambar.
        $product->update(['is_active' => false]);

        return response()->json([
            'message' => 'Produk berhasil dinonaktifkan',
        ]);
    }

}
