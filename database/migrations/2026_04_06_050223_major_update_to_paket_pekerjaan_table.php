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
        Schema::create('penyedia', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nib')->nullable();
            $table->string('npwp')->nullable();
            $table->string('kontak_person')->nullable();
            $table->string('email')->nullable();
            $table->string('notelp')->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });

        Schema::table('paket_pekerjaan', function (Blueprint $table) {
            $table->foreignId('penyedia_id')->nullable()->constrained('penyedia');
            $table->string('tahun_anggaran')->nullable();
            $table->string('nama_program')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('nama_sub_kegiatan')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->double('pagu_dana')->nullable();

            $table->string('no_kontrak')->nullable();
            $table->string('nama_paket')->nullable();
            $table->string('jenis_pengadaan')->nullable();
            $table->string('model_pengadaan')->nullable();
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->double('nilai_pagu')->nullable();
            $table->double('nilai_kontrak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyedia');

        Schema::table('paket_pekerjaan', function (Blueprint $table) {
            $table->dropForeign(['penyedia_id']);
            $table->dropColumn('penyedia_id');
            $table->dropColumn('tahun_anggaran');
            $table->dropColumn('nama_program');
            $table->dropColumn('nama_kegiatan');
            $table->dropColumn('nama_sub_kegiatan');
            $table->dropColumn('nama_rekening');
            $table->dropColumn('pagu_dana');
            $table->dropColumn('no_kontrak');
            $table->dropColumn('nama_paket');
            $table->dropColumn('jenis_pengadaan');
            $table->dropColumn('model_pengadaan');
            $table->dropColumn('tanggal_kontrak');
            $table->dropColumn('tanggal_selesai');
            $table->dropColumn('nilai_pagu');
            $table->dropColumn('nilai_kontrak');
        });
    }
};
