<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataProduct = Product::orderBy('id','ASC')->get();
        foreach ($dataProduct as $dp)
        {
            DB::table('product_picture')->insert([
                'product_id' => $dp->id,
                'productPicture' => "/storage/fotoProduct/dummy.jpg",
            ]);
        }
    }
}
