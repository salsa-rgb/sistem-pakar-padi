<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DiseaseSeeder::class,        // 1. Isi diseases dulu
            SymptomSeeder::class,        // 2. Baru isi symptoms
            DiseaseSymptomSeeder::class, // 3. Terakhir baru relasi
        ]);
    }
}