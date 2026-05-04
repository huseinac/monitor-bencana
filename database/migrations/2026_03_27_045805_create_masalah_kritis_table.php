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
        Schema::create('masalah_kritis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikator');
            $table->foreignId('wilayah_id')->constrained('wilayah');
            $table->text('keterangan');
            $table->integer('jumlah');
            $table->string('satuan')->nullable();
            $table->string('foto')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masalah_kritis');
    }
};
