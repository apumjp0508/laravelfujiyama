<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductSet;

class ProductSetSeeder extends Seeder
{
    public function run()
    {
        ProductSet::factory()->create([
            'name' => 'セット用缶バッチ1',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'img/like-earth-badge.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        ProductSet::factory()->create([
            'name' => 'セット用缶バッチ2',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'img/yossy-badge.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        ProductSet::factory()->create([
            'name' => 'セット用缶バッチ3',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'img/logo-badge.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        ProductSet::factory()->create([
            'name' => 'セット用缶バッチ4',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'img/star-badge.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        ProductSet::factory()->create([
            'name' => 'セット用缶バッチ5',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'img/pink-and-blue-badge.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
    }
}
