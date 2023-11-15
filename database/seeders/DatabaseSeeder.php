<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'provider_id' => 'A1375078684',
            'name' => 'Michael Scott',
            'email' => 'test@example.com',
        ]);
    }
}
