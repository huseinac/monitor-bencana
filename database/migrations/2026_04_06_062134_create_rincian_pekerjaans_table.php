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
        Schema::create('rincian_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_pekerjaan_id')->constrained('paket_pekerjaan');
            $table->string('nama');
            $table->string('satuan');
            $table->decimal('bobot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_pekerjaan');
    }
};
