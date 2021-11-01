<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'Superadmin']);
        Role::create(['name' => 'Supplier']);
        Role::create(['name' => 'Dropshipper']);

        $superadmin = User::create([
            'name' => "Superadmin",
            'email' => "superadmin@pvotdigital.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000000',
            'profilePicture' => '/storage/img/dummyUser.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'userRole' => 'Superadmin'
        ]);
        $superadmin->assignRole('Superadmin');
    }
}
