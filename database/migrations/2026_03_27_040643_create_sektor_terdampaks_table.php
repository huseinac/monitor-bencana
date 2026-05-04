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
        Schema::create('sektor_terdampak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wilayah_id')->constrained('wilayah');
            $table->foreignId('indikator_id')->constrained('indikator');
            $table->foreignId('pelaksana_id')->constrained('pelaksana');
            $table->integer('jumlah');
            $table->string('satuan')->nullable();
            $table->text('kondisi_awal')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sektor_terdampak');
    }
};
