<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants', 'images']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('variants', function ($variantQuery) use ($search) {
                        $variantQuery->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        $categories = [
            'long_sleeve' => 'Long Sleeve',
            'short_sleeve' => 'Short Sleeve',
            'baby_tee' => 'Baby tee',
        ];

        if (
            $request->filled('category') &&
            isset($categories[$request->category])
        ) {
            $query->where('category', $categories[$request->category]);
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'draft') {
            $query->where('is_active', false);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $products->getCollection()->transform(function ($product) {
            $variants = $product->variants;
            $images = $product->images;

            $product->setAttribute(
                'sku',
                $variants->first()?->sku ?? '-'
            );

            $product->setAttribute(
                'price',
                $variants->min('price') ?? 0
            );

            $product->setAttribute(
                'stock',
                $variants->sum('stock')
            );

            $product->setAttribute(
                'image',
                $images->firstWhere('is_primary', true)?->image_path
                    ?? $images->first()?->image_path
            );

            $product->setAttribute(
                'variants_count',
                $variants->pluck('color')->unique()->count()
                    . ' colours · '
                    . $variants->pluck('size')->unique()->count()
                    . ' sizes'
            );

            $product->setAttribute(
                'status',
                $product->is_active ? 'Active' : 'Draft'
            );

            return $product;
        });

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Active', 'Draft'])],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => ['required', 'string', 'max:200'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variants' => ['nullable', 'array'],
            'variants.*' => ['array'],
            'variants.*.*.color' => ['required', 'string', 'max:100'],
            'variants.*.*.size' => ['required', 'string', 'max:50'],
            'variants.*.*.stock' => ['required', 'integer', 'min:0'],
        ]);

        $variantRows = $this->extractVariants($request->input('variants', []));

        if (empty($variantRows)) {
            return back()
                ->withInput()
                ->withErrors(['variants' => 'Tambahkan minimal satu kombinasi warna dan ukuran.']);
        }

        DB::transaction(function () use ($request, $validated, $variantRows) {
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $this->makeUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
                'is_active' => $validated['status'] === 'Active',
            ]);

            foreach ($variantRows as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color' => $variant['color'],
                    'size' => $variant['size'],
                    'price' => $validated['price'],
                    'stock' => $variant['stock'],
                    'sku' => $this->makeUniqueSku(
                        $validated['sku'],
                        $variant['color'],
                        $variant['size']
                    ),
                    'is_active' => true,
                ]);
            }

            foreach ($request->file('images', []) as $index => $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products', 'public'),
                    'alt_text' => $product->name,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(int $id)
    {
        $product = Product::with(['variants', 'images'])
            ->findOrFail($id);

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::with(['variants', 'images'])
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Active', 'Draft'])],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => ['required', 'string', 'max:200'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variants' => ['nullable', 'array'],
            'variants.*' => ['array'],
            'variants.*.*.color' => ['required', 'string', 'max:100'],
            'variants.*.*.size' => ['required', 'string', 'max:50'],
            'variants.*.*.stock' => ['required', 'integer', 'min:0'],
        ]);

        $variantRows = $this->extractVariants($request->input('variants', []));

        if (empty($variantRows)) {
            return back()
                ->withInput()
                ->withErrors(['variants' => 'Tambahkan minimal satu kombinasi warna dan ukuran.']);
        }

        DB::transaction(function () use ($request, $validated, $product, $variantRows) {
            $product->update([
                'name' => $validated['name'],
                'slug' => $this->makeUniqueSlug($validated['name'], $product->id),
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? null,
                'is_active' => $validated['status'] === 'Active',
            ]);

            $keptVariantIds = [];

            foreach ($variantRows as $variant) {
                $existingVariant = ProductVariant::where('product_id', $product->id)
                    ->where('color', $variant['color'])
                    ->where('size', $variant['size'])
                    ->first();

                $sku = $this->makeUniqueSku(
                    $validated['sku'],
                    $variant['color'],
                    $variant['size'],
                    $existingVariant?->id
                );

                $savedVariant = ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $variant['color'],
                        'size' => $variant['size'],
                    ],
                    [
                        'price' => $validated['price'],
                        'stock' => $variant['stock'],
                        'sku' => $sku,
                        'is_active' => true,
                    ]
                );

                $keptVariantIds[] = $savedVariant->id;
            }

            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $keptVariantIds)
                ->update(['is_active' => false]);

            foreach ($request->file('images', []) as $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products', 'public'),
                    'alt_text' => $product->name,
                    'is_primary' => !$product->images()->exists(),
                    'sort_order' => $product->images()->count(),
                ]);
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        // Nonaktifkan produk agar riwayat transaksi tetap aman.
        $product->update(['is_active' => false]);

        $product->variants()->update(['is_active' => false]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product successfully deactivated.');
    }

    private function extractVariants(array $variants): array
    {
        $result = [];

        foreach ($variants as $colorRows) {
            foreach ($colorRows as $variant) {
                if (
                    isset($variant['color'], $variant['size']) &&
                    array_key_exists('stock', $variant)
                ) {
                    $result[] = [
                        'color' => trim($variant['color']),
                        'size' => trim($variant['size']),
                        'stock' => (int) $variant['stock'],
                    ];
                }
            }
        }

        return $result;
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $number = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $number++;
            $slug = $base . '-' . $number;
        }

        return $slug;
    }

    private function makeUniqueSku(
        string $prefix,
        string $color,
        string $size,
        ?int $ignoreId = null
    ): string {
        $base = Str::upper(
            Str::slug($prefix . '-' . $color . '-' . $size)
        );

        $sku = $base;
        $number = 1;

        while (
            ProductVariant::where('sku', $sku)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $number++;
            $sku = $base . '-' . $number;
        }

        return $sku;
    }
}
