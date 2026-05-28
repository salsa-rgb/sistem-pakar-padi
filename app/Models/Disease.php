<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    // Supaya data bisa masuk otomatis saat seeding
    protected $guarded = [];

    /**
     * Relasi ke tabel gejala (Many-to-Many)
     */
    public function symptoms()
    {
        // Gunakan 'disease_symptom' sebagai nama tabel penghubung
        return $this->belongsToMany(Symptom::class, 'disease_symptom');
    }
}