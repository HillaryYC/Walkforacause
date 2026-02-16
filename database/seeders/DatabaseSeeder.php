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
        $shouldSeedAdmin = app()->environment('local') || env('SEED_ADMIN_ON_PROD', false);

        if (!$shouldSeedAdmin) {
            return;
        }

        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        if (!$adminEmail || !$adminPassword) {
            return;
        }

        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Admin',
                'password' => Hash::make($adminPassword),
                'role' => 'super_admin',
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
