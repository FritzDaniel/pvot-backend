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
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        //Permission::create(['name' => 'edit articles']);
        //Permission::create(['name' => 'delete articles']);
        //Permission::create(['name' => 'publish articles']);
        //Permission::create(['name' => 'unpublish articles']);

        $role = Role::create(['name' => 'Superadmin']);
        $role = Role::create(['name' => 'Supplier']);
        $role = Role::create(['name' => 'Dropshipper']);

        $superadmin = User::create([
            'name' => "Superadmin",
            'email' => "superadmin@pvotdigital.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000000',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $superadmin->assignRole('Superadmin');

        $supplier = User::create([
            'name' => "Supplier1",
            'email' => "supplier1@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $supplier->assignRole('Supplier');

        $supplier2 = User::create([
            'name' => "Supplier2",
            'email' => "supplier2@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000002',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $supplier2->assignRole('Supplier');

        $supplier3 = User::create([
            'name' => "Supplier3",
            'email' => "supplier3@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000003',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $supplier3->assignRole('Supplier');

        $supplier4 = User::create([
            'name' => "Supplier4",
            'email' => "supplier4@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000004',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $supplier4->assignRole('Supplier');

        $supplier5 = User::create([
            'name' => "Supplier5",
            'email' => "supplier5@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '0000000005',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $supplier5->assignRole('Supplier');

        $dropshipper = User::create([
            'name' => "Fritz Daniel",
            'email' => "danielsinaga53@gmail.com",
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('secret'),
            'phone' => '085892262574',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $dropshipper->assignRole('Dropshipper');
    }
}
