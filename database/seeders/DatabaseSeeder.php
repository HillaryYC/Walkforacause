<?php

namespace Database\Seeders;

use App\Models\Cause;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
            ]
        );

        Cause::firstOrCreate(
            ['name' => 'Cancer awareness'],
            [
                'description' => 'Support awareness and fundraising for cancer research.',
            ]
        );

        Cause::firstOrCreate(
            ['name' => 'Endangered animals'],
            [
                'description' => 'Raise awareness for endangered wildlife conservation.',
            ]
        );
    }
}
