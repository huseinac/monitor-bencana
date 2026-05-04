<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggaran_daerah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id');
            $table->double('anggaran_2025');
            $table->double('anggaran_2026');
            $table->double('penyesuaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_daerah');
    }
};
