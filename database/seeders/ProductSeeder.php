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
            'name' => 'ダウン',
            'description' => '着ると暖かい',
            'category' => 'ジャケット',
            'price' => 15000,
            'shipping_fee' => 500,
            'stock' => 10,
            'img' => 'storage/img/rwLn7y3w4C3soq71TMop2HFFgao8do4pCTl5iZt7.png',
            'productType' => 'normal',
            'setNum' => null
        ]);
        Product::factory()->create([
            'name' => '缶バッチ4個セット',
            'description' => '缶バッチ4個セット',
            'category' => 'セット商品',
            'price' => 1500,
            'shipping_fee' => 200,
            'stock' => 10,
            'img' => 'storage/img/I0UwK24EhZHRtipvXcIb4qCIZIgMb2WuOa4797JO.png',
            'productType' => 'set',
            'setNum' => '4'
        ]);
    }
}
