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
        Schema::table('indikator', function (Blueprint $table) {
            $table->string('satuan')->nullable();
        });

        Schema::table('sektor_terdampak', function (Blueprint $table) {
            $table->dropColumn('jumlah');
            $table->dropColumn('satuan');
            $table->dropForeign(['pelaksana_id']);
            $table->dropColumn('pelaksana_id');

            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });
        Schema::table('sektor_terdampak', function (Blueprint $table) {
            $table->dropColumn('foto_sebelum');
            $table->dropColumn('foto_sesudah');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('kondisi');
            $table->dropColumn('status');

            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->foreignId('pelaksana_id')->nullable()->constrained('pelaksana');
        });
    }
};
