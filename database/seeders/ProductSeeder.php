<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::factory()->create([
            'name' => 'Badge８',
            'description' => '着ると暖かい',
            'category' => 'Badge6',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'img/pink-and-blue-badge.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        Product::factory()->create([
            'name' => 'Badge4個セット',
            'description' => 'Badge4set',
            'category' => 'Set',
            'price' => 1500,
            'shipping_fee' => 200,
            'stock' => 10,
            'img' => 'storage/img/I0UwK24EhZHRtipvXcIb4qCIZIgMb2WuOa4797JO.png',
            'productType' => 'set',
            'setNum' => '4'
        ]);
        Product::factory()->create([
            'name' => 'Badge',
            'description' => 'Badge',
            'category' => 'Badge1',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'img/star-badge.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        Product::factory()->create([
            'name' => 'Badge',
            'description' => 'Badge',
            'category' => 'Badge2',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'img/logo-badge.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        Product::factory()->create([
            'name' => 'Badge',
            'description' => 'Badge',
            'category' => 'Badge2',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'img/like-earth-badge.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        Product::factory()->create([
            'name' => 'Badge',
            'description' => 'Badge',
            'category' => 'Badge2',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'img/yossy-badge.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        
    }
}
