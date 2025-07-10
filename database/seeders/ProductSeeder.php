<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Nasi Goreng',
                'stock' => 10,
                'price' => 15000,
                'category_id' => 1
            ],
            [
                'name' => 'Teh Botol',
                'stock' => 20,
                'price' => 5000,
                'category_id' => 2
            ],
            [
                'name' => 'Pulpen',
                'stock' => 50,
                'price' => 2000,
                'category_id' => 3
            ],
        ]);
    }
}

