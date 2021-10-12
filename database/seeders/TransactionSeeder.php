<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
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
            DB::table('payments')->insert([
                'external_id' => Str::random(25),
                'user_id' => 7,
                'payment_channel' => "Xendit Invoice",
                'email' => 'danielsinaga53@gmail.com',
                'price' => rand(10000,100000),
                'status' => 'Paid',
                'description' => 'Payment Product'
            ]);

            DB::table('transaction')->insert([
                'user_id' => 7,
                'product_id' => rand(1,20),
                'payment_id' => $i+1,
                'qty' => rand(1,10),
                'transaction_date' => Carbon::now()
            ]);
        }
    }
}
