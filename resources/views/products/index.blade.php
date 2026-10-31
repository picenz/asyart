<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produk Asyart</title>
</head>
<body>
    <h1>Koleksi Asyart</h1>

    @forelse ($products as $product)
        <article>
            <h2>
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h2>

            <p>{{ $product->description }}</p>

            @if ($product->variants->isNotEmpty())
                <p>Mulai dari Rp{{ number_format($product->variants->min('price'), 0, ',', '.') }}</p>
            @else
                <p>Varian belum tersedia.</p>
            @endif
        </article>
    @empty
        <p>Belum ada produk yang tersedia.</p>
    @endforelse

    {{ $products->links() }}
</body>
</html>
