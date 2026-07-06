<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD') ?: 'password';

        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@uranus.local')],
            [
                'name' => env('ADMIN_NAME', 'Uranus Admin'),
                'password' => Hash::make($password),
                'is_active' => true,
            ]
        );

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn('Generated admin password: '.$password);
        }
    }
}
