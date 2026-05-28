<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiseaseSymptomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('disease_symptom')->delete();
        // Format: disease_id => [symptom_id, symptom_id, ...]
        $relations = [
            1 => [1, 2, 3, 4, 23, 24, 25],       
            2 => [5, 6, 7, 10, 26, 27, 29],          
            3 => [11, 13, 14, 19, 2, 12, 25],          
            4 => [8, 15, 16, 23, 28, 32, 33],            
            5 => [34, 18, 16, 7, 25, 30, 31],        
            6 => [8, 16, 17, 20, 21, 2, 28],            
            7 => [9, 2, 20, 21, 3, 25, 24],        
            // sesuaikan dengan data Anda
        ];

        foreach ($relations as $diseaseId => $symptomIds) {
            foreach ($symptomIds as $symptomId) {
                DB::table('disease_symptom')->insert([
                    'disease_id' => $diseaseId,
                    'symptom_id' => $symptomId,
                ]);
            }
        }
    }
}