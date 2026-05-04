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
        Schema::create('kategori_paket_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::table('paket_pekerjaan', function (Blueprint $table) {
            $table->foreignId('kategori_paket_pekerjaan_id')->nullable()->constrained('kategori_paket_pekerjaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_paket_pekerjaan');

        Schema::table('paket_pekerjaan', function (Blueprint $table) {
            $table->dropForeign(['kategori_paket_pekerjaan_id']);
            $table->dropColumn('kategori_paket_pekerjaan_id');
        });
    }
};
