<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Disease;

class DiseaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('disease_symptom')->delete();
        DB::table('diseases')->delete();

        $diseases = [
            [
                'code'     => 'P01',
                'name'     => 'Tungro',
                'solution' => 'Tanam serempak, gunakan varietas tahan tungro...',
            ],
            [
                'code'     => 'P02',
                'name'     => 'Blast',
                'solution' => 'Gunakan varietas tahan blast, atur jarak tanam, gunakan fungisida jika perlu.',
            ],
            [
                'code'     => 'P03',
                'name'     => 'Hawar Daun Bakteri (Kresek)',
                'solution' => 'Gunakan bibit sehat, hindari pemotongan pucuk bibit saat tanam, gunakan pupuk Kalium (K).',
            ],
            [
                'code'     => 'P04',
                'name'     => 'Busuk Pelepah',
                'solution' => 'Atur jarak tanam agar tidak terlalu rapat, bersihkan gulma, gunakan fungisida jika perlu.',
            ],
            [
                'code'     => 'P05',
                'name'     => 'Penggerek Batang',
                'solution' => 'Pengaturan waktu tanam berdasarkan puncak penerbangan ngengat, Pengendalian hayati dapat dilakukan dengan pelepasan parasitoid telur Trichogramma japonicum, gunakan insektisida granular di tanah.',
            ],
            [
                'code'     => 'P06',
                'name'     => 'Hawar Pelepah Daun',
                'solution' => 'Gunakan varietas tahan, kurangi pemupukan N, gunakan agen hayati (Trichoderma).',
            ],
            [
                'code'     => 'P07',
                'name'     => 'Wereng Batang Cokelat',
                'solution' => 'Gunakan varietas tahan (Inpari 33), amati populasi wereng setiap minggu, gunakan agen hayati Beauveria bassiana.',
            ],
            // ... data lainnya
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }
}