<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan baris ini ada di dalam sini:
        $this->call([
            HalteSeeder::class,
        ]);
    }
}