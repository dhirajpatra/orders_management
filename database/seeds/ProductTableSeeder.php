<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $products = [
            [
                'product_name' => 'Pepsi Cola',
                'product_price' => '1.60',
                'product_stock' => '100'
            ],
            [
                'product_name' => 'Coca Cola',
                'product_price' => '1.80',
                'product_stock' => '100'
            ]
        ];

        $db = DB::table('products')->insert($products);
    }
}
