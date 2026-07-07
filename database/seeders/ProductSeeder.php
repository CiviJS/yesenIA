<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
  
        $categoryId = DB::table('product_categories')
            ->where('name', 'Cerveza')
            ->value('id');

        $now = Carbon::now();

        $beers = [

            ['name' => 'Cerveza Águila 330ml', 'price' => 2800, 'stock' => 150],
            ['name' => 'Cerveza Águila Light 330ml', 'price' => 2900, 'stock' => 200],
            ['name' => 'Cerveza Póker 330ml', 'price' => 2800, 'stock' => 180],
            ['name' => 'Cerveza Costeña Bacana 330ml', 'price' => 2300, 'stock' => 100],
            ['name' => 'Cerveza Andina 330ml', 'price' => 2500, 'stock' => 120],
            
          
            ['name' => 'Cerveza Brunonia Lata 330ml', 'price' => 2300, 'stock' => 300],
            ['name' => 'Cerveza Bahía Lata 330ml', 'price' => 2000, 'stock' => 250],
            ['name' => 'Cerveza Weidmann Lata 330ml', 'price' => 1800, 'stock' => 400],
            
            ['name' => 'Cerveza Club Colombia Dorada 330ml', 'price' => 3700, 'stock' => 90],
            ['name' => 'Cerveza Club Colombia Roja 330ml', 'price' => 3700, 'stock' => 80],
            ['name' => 'Cerveza BBC Monserrate 330ml', 'price' => 6500, 'stock' => 45],
            ['name' => 'Cerveza 3 Cordilleras Rosada 330ml', 'price' => 5300, 'stock' => 60],
      
            ['name' => 'Cerveza Corona Extra 355ml', 'price' => 5800, 'stock' => 110],
            ['name' => 'Cerveza Heineken 330ml', 'price' => 5200, 'stock' => 130],
            ['name' => 'Cerveza Stella Artois 330ml', 'price' => 5600, 'stock' => 70],
            ['name' => 'Cerveza Michelob Ultra 330ml', 'price' => 4500, 'stock' => 100],
        ];


        $products = array_map(function ($beer) use ($categoryId, $now) {
            return [
                'product_category_id' => $categoryId,
                'name' => $beer['name'],
                'price' => $beer['price'], 
                'stock' => $beer['stock'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $beers);

        DB::table('products')->insert($products);
    }
}
