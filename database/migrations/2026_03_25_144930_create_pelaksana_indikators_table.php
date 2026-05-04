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
        Schema::create('pelaksana_indikator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained('indikator');
            $table->foreignId('pelaksana_id')->constrained('pelaksana');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaksana_indikator');
    }
};
