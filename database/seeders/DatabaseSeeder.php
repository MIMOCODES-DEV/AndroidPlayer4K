<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\DomainUrl;
use App\Models\ContactDetail;
use App\Models\AppVersion;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Single admin user
        User::create([
            'name'     => 'android',
            'email'    => 'androidplayer4k@gmail.com',
            'password' => Hash::make('Ahmed@22'),
        ]);

        // The 7 original products with slugs matching old API path segments exactly
        $products = [
            ['name' => 'King 4k',               'slug' => 'king4k'],
            ['name' => 'Ibox Tv',               'slug' => 'ibox'],
            ['name' => 'Ibo Player Active Code', 'slug' => 'iboActiveCode'],
            ['name' => 'Media Player Ibo',       'slug' => 'mediaIbo'],
            ['name' => 'Iboss Iptv',             'slug' => 'Iboss'],
            ['name' => 'Ibo Tv Pro',             'slug' => 'IboTvPro'],
            ['name' => '4k Player',              'slug' => '4kplayer'],
        ];

        foreach ($products as $data) {
            $product = Product::create([
                'name'      => $data['name'],
                'slug'      => $data['slug'],
                'is_active' => true,
            ]);

            // Create empty related records so forms always have something to edit
            DomainUrl::create(['product_id' => $product->id, 'url' => null]);
            ContactDetail::create(['product_id' => $product->id, 'email' => null, 'phone' => null, 'info' => null]);
            AppVersion::create(['product_id' => $product->id, 'version' => null, 'description' => null, 'file' => null]);
        }
    }
}
