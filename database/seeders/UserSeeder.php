<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'admin']); // Admin role
        Role::create(['name' => 'director']); // Director role
        Role::create(['name' => 'employee']); // Employee role
        Role::create(['name' => 'resource']); // Resource role
        Role::create(['name' => 'headOfDivision']); // headOfDivision role

        // Create admin user
        User::factory()->admin()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com'
        ]);

        // Create director user
        User::factory()->director()->create([
            'name' => 'director',
            'email' => 'director@gmail.com'
        ]);

        // Create employee user
        User::factory()->employee()->create([
            'name' => 'employee',
            'email' => 'employee@gmail.com'
        ]);

        // Create resource user
        User::factory()->resource()->create([
            'name' => 'resource',
            'email' => 'resource@gmail.com'
        ]);

        // Create head of division user
        User::factory()->headOfDivision()->create([
            'name' => 'headOfDivision',
            'email' => 'headOfDivision@gmail.com'
        ]);
    }
}
