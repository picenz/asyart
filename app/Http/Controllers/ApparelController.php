<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApparelController extends Controller
{
    private $katalog = [
        [
            'id' => 0,
            'nama' => 'Crop Top Bang Windah',
            'kategori' => 'Crop Top',
            'harga' => 'Rp90.000',
            'gambar_hitam' => 'windahcrop-htm.jpg',
            'gambar_putih' => 'windahcrop-pth.jpg'
        ],
        [
            'id' => 1,
            'nama' => 'Regular Tee Bocil Bang Windah',
            'kategori' => 'Short Sleeve',
            'harga' => 'Rp85.000',
            'gambar_hitam' => 'windahreg-htm.jpg',
            'gambar_putih' => 'windahreg-pth.jpg'
        ],
        [
            'id' => 2,
            'nama' => 'Longsleeve Bang Windah',
            'kategori' => 'Long Sleeve',
            'harga' => 'Rp110.000',
            'gambar_hitam' => 'windahpnjg-htm.jpg',
            'gambar_putih' => 'windahpnjg-pth.jpg'
        ],
        [
            'id' => 3,
            'nama' => 'Crop Top Snoopy',
            'kategori' => 'Crop Top',
            'harga' => 'Rp90.000',
            'gambar_hitam' => 'snpcrop-htm.jpg',
            'gambar_putih' => 'snpcrop-pth.jpg'
        ],
        [
            'id' => 4,
            'nama' => 'Crop Top Radio Head',
            'kategori' => 'Crop Top',
            'harga' => 'Rp85.000',
            'gambar_hitam' => 'radiocrop-htm.jpg',
            'gambar_putih' => 'radiocrop-pth.jpg'
        ],
        [
            'id' => 5,
            'nama' => 'Crop Top Cake',
            'kategori' => 'Crop Top',
            'harga' => 'Rp110.000',
            'gambar_hitam' => 'cakecrop-htm.jpg',
            'gambar_putih' => 'cakecrop-pth.jpg'
        ],
        [
            'id' => 6,
            'nama' => 'Crop Top Blue Star Fish',
            'kategori' => 'Crop Top',
            'harga' => 'Rp95.000',
            'gambar_hitam' => 'ikanbirucrop-htm.jpg',
            'gambar_putih' => 'ikanbirucrop-pth.jpg'
        ],
        [
            'id' => 7,
            'nama' => 'Regular Tee Circle Fish',
            'kategori' => 'Short Sleeve',
            'harga' => 'Rp85.000',
            'gambar_hitam' => 'ikanbulatreg-htm.jpg',
            'gambar_putih' => 'ikanbulatreg-pth.jpg'
        ],
        [
            'id' => 8,
            'nama' => 'Longsleeve Puffer Fish',
            'kategori' => 'Long Sleeve',
            'harga' => 'Rp115.000',
            'gambar_hitam' => 'ikanbuntalpnjg-htm.jpg',
            'gambar_putih' => 'ikanbuntalpnjg-pth.jpg'
        ],
        [
            'id' => 9,
            'nama' => 'Crop Top Snoopy & Bang Windah',
            'kategori' => 'Crop Top',
            'harga' => 'Rp75.000',
            'gambar_hitam' => 'npcrop-htm.jpg',
            'gambar_putih' => 'npcrop-pth.jpg'
        ],
        [
            'id' => 10,
            'nama' => 'Regular Tee Bocil Sigma',
            'kategori' => 'Regular Tee',
            'harga' => 'Rp90.000',
            'gambar_hitam' => 'bclreg-htm.jpg',
            'gambar_putih' => 'bclreg-pth.jpg'
        ],
        [
            'id' => 11,
            'nama' => 'Longsleeve Orange Fish',
            'kategori' => 'Long Sleeve',
            'harga' => 'Rp80.000',
            'gambar_hitam' => 'ikanorenpnjg-htm.jpg',
            'gambar_putih' => 'ikanorenpnjg-pth.jpg'
        ],
    ];

    public function index()
    {
        $katalog = $this->katalog;
        return view('home', compact('katalog'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $query = $keyword; // Alias agar kompatibel dengan view search

        // Filter produk berdasarkan keyword pencarian
        $results = collect($this->katalog)->filter(function ($item) use ($keyword) {
            if (empty($keyword)) return false;
            return str_contains(strtolower($item['nama']), strtolower($keyword)) ||
                str_contains(strtolower($item['kategori']), strtolower($keyword));
        });

        return view('search', compact('keyword', 'query', 'results'));
    }

    public function cart()
    {
        return view('cart'); // Mengarah ke view bag/cart yang konsisten
    }

    public function show($id)
    {
        $product = $this->katalog[$id] ?? abort(404);
        return view('detail-product', compact('product'));
    }
}
