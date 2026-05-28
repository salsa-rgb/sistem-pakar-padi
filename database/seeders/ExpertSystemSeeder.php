<?php

namespace Database\Seeders;

use App\Models\Symptom;
use App\Models\Disease;
use Illuminate\Database\Seeder;

class ExpertSystemSeeder extends Seeder
{
    public function run(): void
    {
        // --- DAFTAR 43 GEJALA (SYMPTOMS) ---
        $g = [];
        $symptomsData = [
            ['G01', 'Tanaman kerdil'],
            ['G02', 'Daun menguning mulai dari ujung'],
            ['G03', 'Anakan berkurang'],
            ['G04', 'Daun berwarna oranye/jingga'],
            ['G05', 'Bercak cokelat berbentuk belah ketupat pada daun'],
            ['G06', 'Leher malai membusuk/patah (Blas Leher)'],
            ['G07', 'Gabah hampa/tidak terisi'],
            ['G08', 'Terdapat bercak abu-abu pada pelepah'],
            ['G09', 'Tanaman seperti terbakar (Hopperburn)'],
            ['G10', 'Adanya massa jamur putih/merah muda pada bulir'],
            ['G11', 'Daun menggulung'],
            ['G12', 'Tepi daun bergerigi/rusak'],
            ['G13', 'Garis kuning keputihan sejajar ibu tulang daun'],
            ['G14', 'Tanaman layu mendadak (Kresek)'],
            ['G15', 'Butir gabah berubah warna jadi cokelat kotor'],
            ['G16', 'Batang menjadi lemah dan mudah rebah'],
            ['G17', 'Munculnya garis merah pada pelepah daun'],
            ['G18', 'Malai mati dan berwarna putih (Beluk)'],
            ['G19', 'Daun mati mulai dari pinggir'],
            ['G20', 'Pertumbuhan melambat'],
            ['G21', 'Warna daun pucat'],
            ['G22', 'Batang mengecil'],
            ['G23', 'Butir gabah bercak hitam'],
            ['G24', 'Adanya cairan manis (Honeydew) pada pangkal batang'],
            ['G25', 'Tanaman mengering dengan cepat'],
            ['G26', 'Warna malai berubah abu-abu'],
            ['G27', 'Daun bendera mengering'],
            ['G28', 'Pelepah daun mengerut'],
            ['G29', 'Buku batang menghitam'],
            ['G30', 'Terdapat lubang kecil pada batang'],
            ['G31', 'Tanaman mudah dicabut'],
            ['G32', 'Adanya bau busuk pada tanaman'],
            ['G33', 'Muncul benang putih seperti kapas di sela daun'],
            ['G34', 'terdapat ulat dalam batang'],
            
        ];

        $g = []; // Pastikan variabel ini sudah didefinisikan di atas loop
        
        foreach ($symptomsData as $data) {
            // 1. Simpan ke database
            $symptom = Symptom::updateOrCreate(
                ['code' => $data[0]], 
                ['description' => $data[1]]
            );
            
            // 2. MASUKKAN KE ARRAY $g (Ini yang kurang tadi!)
            $g[$data[0]] = $symptom;
        }

        // --- 2. DAFTAR PENYAKIT UTAMA & RULES ---

        // P01: TUNGRO
        $p01 = Disease::updateOrCreate(['code' => 'P01'], [
            'name' => 'Tungro',
            'solution' => 'Tanam serempak, gunakan varietas tahan (Inpari 36), kendalikan vektor wereng hijau dengan insektisida.'
        ]);
        $p01->symptoms()->sync([$g['G01']->id, $g['G02']->id, $g['G03']->id, $g['G04']->id, $g['G23']->id, $g['G24']->id, $g['G25']->id]);

        // P02: BLAST
        $p02 = Disease::updateOrCreate(['code' => 'P02'], [
            'name' => 'Blast (Pyricularia oryzae)',
            'solution' => 'Gunakan fungisida sistemik, bakar sisa tanaman terinfeksi, kurangi dosis pupuk Nitrogen.'
        ]);
        $p02->symptoms()->sync([$g['G05']->id, $g['G06']->id, $g['G07']->id, $g['G10']->id, $g['G26']->id, $g['G27']->id, $g['G29']->id]);

        // P03: HAWAR DAUN BAKTERI (HDB)
        $p03 = Disease::updateOrCreate(['code' => 'P03'], [
            'name' => 'Hawar Daun Bakteri (Kresek)',
            'solution' => 'Gunakan bibit sehat, hindari pemotongan pucuk bibit saat tanam, gunakan pupuk Kalium (K).'
        ]);
        $p03->symptoms()->sync([$g['G13']->id, $g['G14']->id, $g['G11']->id, $g['G12']->id, $g['G02']->id, $g['G19']->id, $g['G25']->id]);

        // P04: BUSUK PELEPAH
        $p04 = Disease::updateOrCreate(['code' => 'P04'], [
            'name' => 'Busuk Pelepah',
            'solution' => 'Atur jarak tanam agar tidak terlalu rapat, bersihkan gulma, gunakan fungisida jika perlu.'
        ]);
        $p04->symptoms()->sync([$g['G08']->id, $g['G15']->id, $g['G16']->id, $g['G23']->id, $g['G28']->id, $g['G32']->id, $g['G33']->id]);

        // P05: PENGGEREK BATANG (BELUK/SUNDEP)
        $p05 = Disease::updateOrCreate(['code' => 'P05'], [
            'name' => 'Penggerek Batang',
            'solution' => 'Pengaturan waktu tanam berdasarkan puncak penerbangan ngengat, Pengendalian hayati dapat dilakukan dengan pelepasan parasitoid telur Trichogramma japonicum, gunakan insektisida granular di tanah.'
        ]);
        $p05->symptoms()->sync([$g['G34']->id, $g['G18']->id, $g['G16']->id, $g['G07']->id, $g['G25']->id, $g['G30']->id, $g['G31']->id]);

        // P06: HAWAR PELEPAH (SHREATH BLIGHT)
        $p06 = Disease::updateOrCreate(['code' => 'P06'], [
            'name' => 'Hawar Pelepah Daun',
            'solution' => 'Gunakan varietas tahan, kurangi pemupukan N, gunakan agen hayati (Trichoderma).'
        ]);
        $p06->symptoms()->sync([$g['G08']->id, $g['G16']->id, $g['G17']->id, $g['G20']->id, $g['G21']->id, $g['G02']->id, $g['G28']->id]);

        // P07: WERENG COKELAT (WBC)
        $p07 = Disease::updateOrCreate(['code' => 'P07'], [
            'name' => 'Wereng Batang Cokelat',
            'solution' => 'Gunakan varietas tahan (Inpari 33), amati populasi wereng setiap minggu, gunakan agen hayati Beauveria bassiana.'
        ]);
        $p07->symptoms()->sync([$g['G09']->id, $g['G02']->id, $g['G20']->id, $g['G21']->id, $g['G03']->id, $g['G25']->id, $g['G24']->id]);
    }
}