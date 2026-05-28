<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['code', 'description'];
    use HasFactory;

    protected $guarded = [];

    public function symptoms()
{
    // Cukup begini saja tanpa ->withPivot()
    return $this->belongsToMany(Symptom::class, 'disease_symptom');
}
}
