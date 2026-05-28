<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Symptom;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('symptoms')->delete();

        $symptoms = [
            ['code' => 'G01', 'description' => 'Tanaman kerdil'],
            ['code' => 'G02', 'description' => 'Daun berwarna menguning mulai dari ujung'],
            ['code' => 'G03', 'description' => 'Anakan berkurang'],
            ['code' => 'G04', 'description' => 'Daun berwarna oranye/jingga'],
            ['code' => 'G05', 'description' => 'Bercak cokelat berbentuk belah ketupat pada daun'],
            ['code' => 'G06', 'description' => 'Leher malai membusuk/patah (Blas Leher)'],
            ['code' => 'G07', 'description' => 'Gabah hampa/tidak terisi'],
            ['code' => 'G08', 'description' => 'Terdapat bercak abu-abu pada pelepah'],
            ['code' => 'G09', 'description' => 'Tanaman seperti terbakar (Hopperburn)'],
            ['code' => 'G10', 'description' => 'Adanya massa jamur putih/merah muda pada bulir'],
            ['code' => 'G11', 'description' => 'Daun menggulung'],
            ['code' => 'G12', 'description' => 'Tepi daun bergerigi/rusak'],
            ['code' => 'G13', 'description' => 'Garis kuning keputihan sejajar ibu tulang daun'],
            ['code' => 'G14', 'description' => 'Tanaman layu mendadak (Kresek)'],
            ['code' => 'G15', 'description' => 'Butir gabah berubah warna jadi cokelat kotor'],
            ['code' => 'G16', 'description' => 'Batang menjadi lemah dan mudah rebah'],
            ['code' => 'G17', 'description' => 'Munculnya garis merah pada pelepah daun'],
            ['code' => 'G18', 'description' => 'Malai mati dan berwarna putih (Beluk)'],
            ['code' => 'G19', 'description' => 'Daun mati mulai dari pinggir'],
            ['code' => 'G20', 'description' => 'Pertumbuhan melambat'],
            ['code' => 'G21', 'description' => 'Warna daun pucat'],
            ['code' => 'G22', 'description' => 'Batang mengecil'],
            ['code' => 'G23', 'description' => 'Butir gabah bercak hitam'],
            ['code' => 'G24', 'description' => 'Adanya cairan manis (Honeydew) pada pangkal batang'],
            ['code' => 'G25', 'description' => 'Tanaman mengering dengan cepat'],
            ['code' => 'G26', 'description' => 'Warna malai berubah abu-abu'],
            ['code' => 'G27', 'description' => 'daun bendera mengering'],
            ['code' => 'G28', 'description' => 'Pelepah daun mengerut'],
            ['code' => 'G29', 'description' => 'Buku batang menghitam'],
            ['code' => 'G30', 'description' => 'Terdapat lubang kecil pada batang'],
            ['code' => 'G31', 'description' => 'Tanaman mudah dicabut'],
            ['code' => 'G32', 'description' => 'Adanya bau busuk pada tanaman'],
            ['code' => 'G33', 'description' => 'Muncul benang putih seperti kapas di sela daun'],
            ['code' => 'G34', 'description' => 'terdapat ulat dalam batang'],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::create($symptom);
        }
    }
}