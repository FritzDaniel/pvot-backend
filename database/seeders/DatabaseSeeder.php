<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(DesignSeeder::class);
        $this->call(ProductPhotoSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
