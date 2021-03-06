<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<20; $i++)
        {
            $productPrice = rand(10000,100000);
            $productRevenue = $productPrice * (5/100);
            DB::table('product')->insert([
                'user_id' => rand(2,6),
                'productName' => Str::random(10),
                'productDesc' => Str::random(50),
                'productQty' => rand(10,100),
                'productPrice' => $productPrice,
                'productRevenue' => ceil($productRevenue),
                'showPrice' => $productPrice + ceil($productRevenue)
            ]);
        }
    }
}
