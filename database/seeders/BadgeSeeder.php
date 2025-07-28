<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        Badge::factory()->create([
            'name' => 'セット用缶バッチ1',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'storage/img/uMoKXdDUV4Wj4VuUCfxaRMpO1bxhF97qgaYI575N.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        Badge::factory()->create([
            'name' => 'セット用缶バッチ2',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'storage/img/SCVhItzqW3EEttA8Evx4kPmIAm9otEw5A2fFIBOy.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        Badge::factory()->create([
            'name' => 'セット用缶バッチ3',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'storage/img/x5cWBHlaomPkhO9D5KhZc8n2yX1899FWgY45xN4K.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        Badge::factory()->create([
            'name' => 'セット用缶バッチ4',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'storage/img/UTTyZfEivhj1cuddprIZ9nEq8YE9Pvr7mTWlDXAk.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
        Badge::factory()->create([
            'name' => 'セット用缶バッチ5',
            'description' => 'セット用缶バッチ',
            'stock' => 10,
            'img' => 'storage/img/WVDVl86DUfaO9slQZqwxEtbMkV9R9N1rbwQ8VFqn.png',
            'widthSize' => '50',
            'heightSize' => '50'
        ]);
    }
}
