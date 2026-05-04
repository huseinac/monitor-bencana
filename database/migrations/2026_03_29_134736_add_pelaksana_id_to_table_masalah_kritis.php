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
        Schema::table('masalah_kritis', function (Blueprint $table) {
            $table->unsignedBigInteger('indikator_id')->nullable()->change();
            $table->foreignId('pelaksana_id')->nullable()->constrained('pelaksana');
            $table->string('foto_sesudah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masalah_kritis', function (Blueprint $table) {
            $table->dropForeign(['pelaksana_id']);
            $table->dropColumn('pelaksana_id');
            $table->dropColumn('foto_sesudah');
        });
    }
};
