<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $agent = User::updateOrCreate(
            ['email' => 'agent@support.com'],
            [
                'name' => 'Sarah Agent',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
