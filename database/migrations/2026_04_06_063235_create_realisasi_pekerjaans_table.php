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
        Schema::create('realisasi_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rincian_pekerjaan_id')->constrained('rincian_pekerjaan');
            $table->date('tanggal');
            $table->integer('realisasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_pekerjaan');
    }
};
