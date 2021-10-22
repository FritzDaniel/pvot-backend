<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('variant')->insert([
            'variantType' => 'Jenis',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('variant')->insert([
            'variantType' => 'Warna',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('variant')->insert([
            'variantType' => 'Ukuran',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('variant')->insert([
            'variantType' => 'Variasi',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
