<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<40; $i++)
        {
            DB::table('category')->insert([
                'name' => Str::random(10),
                'logo' => "/storage/fotoKategori/dummy.jpg"
            ]);
        }

        for ($i=0; $i<40; $i++)
        {
            DB::table('sub_category')->insert([
                'category_id' => rand(1, 40),
                'name' => Str::random(10),
            ]);
        }

        for ($i=0; $i<40; $i++)
        {
            DB::table('sub_child_category')->insert([
                'sub_category_id' => rand(1, 40),
                'name' => Str::random(10),
            ]);
        }
    }
}
