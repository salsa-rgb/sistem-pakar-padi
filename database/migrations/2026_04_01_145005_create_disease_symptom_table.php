<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_symptom', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel diseases
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            // Menghubungkan ke tabel symptoms
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_symptom');
    }
};