<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
});

Route::middleware('io_auth_api')->name('api.')->group(function () {
    ioRouteResourceApi('user', App\Http\Controllers\Api\UserController::class);
    ioRouteResourceApi('wilayah', App\Http\Controllers\Api\WilayahController::class);
    ioRouteResourceApi('indikator', App\Http\Controllers\Api\IndikatorController::class);
    ioRouteResourceApi('pelaksana', App\Http\Controllers\Api\PelaksanaController::class);
    ioRouteResourceApi('sektor_terdampak', App\Http\Controllers\Api\SektorTerdampakController::class);
    ioRouteResourceApi('sektor_terdampak_detail', App\Http\Controllers\Api\SektorTerdampakDetailController::class);
    ioRouteResourceApi('perbaikan', App\Http\Controllers\Api\PerbaikanController::class);
    ioRouteResourceApi('masalah_kritis', App\Http\Controllers\Api\MasalahKritisController::class);
    ioRouteResourceApi('paket_pekerjaan', App\Http\Controllers\Api\PaketPekerjaanController::class);
    ioRouteResourceApi('anggaran_daerah', App\Http\Controllers\Api\AnggaranDaerahController::class);
    ioRouteResourceApi('alokasi_anggaran', App\Http\Controllers\Api\AlokasiAnggaranController::class);
    ioRouteResourceApi('kategori_paket_pekerjaan', App\Http\Controllers\Api\KategoriPaketPekerjaanController::class);
    ioRouteResourceApi('penyedia', App\Http\Controllers\Api\PenyediaController::class);
    ioRouteResourceApi('rincian_pekerjaan', App\Http\Controllers\Api\RincianPekerjaanController::class);
    ioRouteResourceApi('timeline_pekerjaan', App\Http\Controllers\Api\TimelinePekerjaanController::class);
    ioRouteResourceApi('realisasi_pekerjaan', App\Http\Controllers\Api\RealisasiPekerjaanController::class);
    ioRouteResourceApi('pembayaran_pekerjaan', App\Http\Controllers\Api\PembayaranPekerjaanController::class);
    ioRouteResourceApi('pelaksana_indikator', App\Http\Controllers\Api\PelaksanaIndikatorController::class);
});

Route::prefix('excel')
    ->middleware('excel_auth_api')
    ->group(function () {
        Route::get('/wilayah/{scope}', [App\Http\Controllers\PaketPekerjaanController::class, 'listWilayahExcel'])->name('listWilayahExcel');
        Route::get('/wilayah/{scope}/{children}', [App\Http\Controllers\PaketPekerjaanController::class, 'listWilayahExcel'])->name('listWilayahExcel');
        Route::get('/indikator', [App\Http\Controllers\PaketPekerjaanController::class, 'listIndikatorExcel'])->name('listIndikatorExcel');
        Route::get('/pelaksana', [App\Http\Controllers\PaketPekerjaanController::class, 'listPelaksanaExcel'])->name('listPelaksanaExcel');
        Route::get('/kategori_paket', [App\Http\Controllers\PaketPekerjaanController::class, 'kategoriPaketExcel'])->name('kategoriPaketExcel');
        Route::get('/daftar_penyedia', [App\Http\Controllers\PaketPekerjaanController::class, 'daftarPenyediaExcel'])->name('daftarPenyediaExcel');
});