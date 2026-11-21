<?php

namespace App;

class ProductStore
{
    public static function products(): array
    {
        return [
            0 => [
                'id' => 0,
                'name' => 'Baby Tee Bang Windah',
                'category' => 'BABY TEE',
                'price' => 90000,
                'rating' => 4.9,
                'reviews_count' => 128,
            ],
            3 => [
                'id' => 3,
                'name' => 'Baby Tee Radio Head',
                'category' => 'BABY TEE',
                'price' => 85000,
                'rating' => 4.8,
                'reviews_count' => 64,
            ],
            4 => [
                'id' => 4,
                'name' => 'Short Sleeve Bocil Bang Windah',
                'category' => 'SHORT SLEEVE',
                'price' => 85000,
                'rating' => 4.9,
                'reviews_count' => 92,
            ],
            5 => [
                'id' => 5,
                'name' => 'Longsleeve Bang Windah',
                'category' => 'LONG SLEEVE',
                'price' => 110000,
                'rating' => 4.7,
                'reviews_count' => 41,
            ],
            6 => [
                'id' => 6,
                'name' => 'Baby Tee Snoopy',
                'category' => 'BABY TEE',
                'price' => 90000,
                'rating' => 4.8,
                'reviews_count' => 53,
            ],
        ];
    }

    public static function reviewsByProduct(): array
    {
        return [
            0 => [
                [
                    'author' => 'Gilang Ramadhan',
                    'time' => '2 weeks ago',
                    'rating' => 5,
                    'comment' => 'Gokil sih merch-nya Bang Windah, bahan cotton combed-nya beneran premium.',
                ],
                [
                    'author' => 'Dinda Kirana',
                    'time' => '1 week ago',
                    'rating' => 5,
                    'comment' => 'Pengiriman cepet banget, ukuran M pas di badan, warnanya cakep ga luntur pas dicuci.',
                ],
            ],
            3 => [
                [
                    'author' => 'Raka Pratama',
                    'time' => '4 days ago',
                    'rating' => 5,
                    'comment' => 'Sablon Radio Head-nya tajam, potongannya pas buat daily look.',
                ],
            ],
            4 => [
                [
                    'author' => 'Nadia Putri',
                    'time' => '6 days ago',
                    'rating' => 5,
                    'comment' => 'Desain bocilnya kocak tapi bahannya tetap adem dipakai seharian.',
                ],
            ],
            5 => [
                [
                    'author' => 'Fajar Nugroho',
                    'time' => '3 days ago',
                    'rating' => 4,
                    'comment' => 'Longsleeve-nya nyaman, lengan nggak kedodoran.',
                ],
            ],
            6 => [
                [
                    'author' => 'Salsa Aurelia',
                    'time' => '5 days ago',
                    'rating' => 5,
                    'comment' => 'Snoopy-nya gemes banget, jahitan rapi dan bahannya lembut.',
                ],
            ],
        ];
    }

    public static function findProduct(int|string $id): array
    {
        $products = self::products();
        $key = (int) $id;

        abort_unless(isset($products[$key]), 404);

        return $products[$key];
    }

    public static function findReviews(int|string $id): array
    {
        $reviews = self::reviewsByProduct();
        $key = (int) $id;

        return $reviews[$key] ?? [];
    }
}