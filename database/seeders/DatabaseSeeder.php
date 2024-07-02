<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\House;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $adminRole = Role::where('name', 'Admin')->first();
        $propertyManagerRole = Role::where('name', 'Property Manager')->first();
        $landlordRole = Role::where('name', 'Landlord')->first();
        $maintenanceWorkerRole = Role::where('name', 'Maintenance Worker')->first();
        $accountantRole = Role::where('name', 'Accountant')->first();
        $tenantRole = Role::where('name', 'Tenant')->first();

        // Admin Data Seeded
        User::factory()->create([
            'name' => 'Elvis Makara',
            'email' => 'makarawahome@gmail.com',
            'roles_id' => $adminRole->id,
            'phone_number' => '+254 701727560',
            'id_number' => '41824185',
            'password' => Hash::make('123456789')
        ]);

        User::factory()->create([
            'name' => 'Dennis Wanjiku',
            'email' => 'dennis.wanjiku@strathmore.edu',
            'roles_id' => $adminRole->id,
            'phone_number' => '+254743614394',
            'id_number' => '41191771',
            'password' => Hash::make('123456789')
        ]);

        // Property Manager Seeded
        User::factory()->create([
            'email' => 'propertymanager@example.com',
            'roles_id' => $propertyManagerRole->id,
            'password' => Hash::make('password')
        ]);

        // Landlord Data Seeded
        User::factory()->create([
            'email' => 'landlord@example.com',
            'roles_id' => $landlordRole->id,
            'password' => Hash::make('password')
        ]);

        // Maintenance Worker Seeded
        User::factory()->create([
            'email' => 'maintenance@example.com',
            'roles_id' => $maintenanceWorkerRole->id,
            'password' => Hash::make('password')
        ]);

        // Accountant Data Seeded
        User::factory()->create([
            'email' => 'accountant@example.com',
            'roles_id' => $accountantRole->id,
            'password' => Hash::make('password')
        ]);

        // Tenant Data Seeded (unverified)
        User::factory()->count(5)->create([
            'roles_id' => $tenantRole->id,
            'email_verified_at' => null, // For unverified users
        ]);

        // Tenant Data Seeded
        User::factory()->count(2)->create([
            'roles_id' => $tenantRole->id,
        ]);

        $this->call(CategorySeeder::class);
        $this->call(HouseWithImageSeeder::class);
        
    }
}