<?php

use App\Http\Controllers\NewHomeController;
use Illuminate\Support\Facades\Route;

ini_set('memory_limit', '4024M');

Route::get('/', [App\Http\Controllers\HomeController::class, 'map'])->name('/');
Route::get('hapus_cache/{kode?}', [App\Http\Controllers\HomeController::class, 'hapus_cache'])->name('hapus_cache');
Route::get('map/get_wilayah', [App\Http\Controllers\HomeController::class, 'get_wilayah'])->name('map.get_wilayah');
Route::get('map/get_wilayah_all', [App\Http\Controllers\HomeController::class, 'get_wilayah_all'])->name('map.get_wilayah_all');
Route::get('map/get_indikator', [App\Http\Controllers\HomeController::class, 'get_indikator'])->name('map.get_indikator');
Route::get('map/get_pekerjaan', [App\Http\Controllers\HomeController::class, 'get_pekerjaan'])->name('map.get_pekerjaan');
Route::get('map/get_anggaran', [App\Http\Controllers\HomeController::class, 'get_anggaran'])->name('map.get_anggaran');
Route::get('map/detail_pekerjaan/{id}', [App\Http\Controllers\HomeController::class, 'detail_pekerjaan'])->name('map.detail_pekerjaan');

Route::get('new_map', [NewHomeController::class, 'index'])->name('new_map');

Route::get('download_asset', [App\Http\Controllers\HomeController::class, 'download_asset'])->name('map.download_asset');

Route::get('login/{role?}', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login_process'])->name('login.process');
Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['io', 'auth'])->group(function () {
    Route::view('admin', 'admin')->name('admin');
    ioRouteResource('user', App\Http\Controllers\UserController::class);
    ioRouteResource('wilayah', App\Http\Controllers\WilayahController::class);
    ioRouteResource('indikator', App\Http\Controllers\IndikatorController::class);
    ioRouteResource('pelaksana', App\Http\Controllers\PelaksanaController::class);
    ioRouteResource('sektor_terdampak', App\Http\Controllers\SektorTerdampakController::class);
    ioRouteResource('perbaikan', App\Http\Controllers\PerbaikanController::class);
    ioRouteResource('masalah_kritis', App\Http\Controllers\MasalahKritisController::class);
    ioRouteResource('paket_pekerjaan', App\Http\Controllers\PaketPekerjaanController::class);
    ioRouteResource('anggaran_daerah', App\Http\Controllers\AnggaranDaerahController::class);
    ioRouteResource('alokasi_anggaran', App\Http\Controllers\AlokasiAnggaranController::class);
    ioRouteResource('kategori_paket_pekerjaan', App\Http\Controllers\KategoriPaketPekerjaanController::class);
    ioRouteResource('penyedia', App\Http\Controllers\PenyediaController::class);

    Route::post('paket_pekerjaan/import_data', [App\Http\Controllers\PaketPekerjaanController::class, 'import_data'])->name('paket_pekerjaan.import_data');
    Route::post('sektor_terdampak/import_data', [App\Http\Controllers\SektorTerdampakController::class, 'import_data'])->name('sektor_terdampak.import_data');
    Route::post('sektor_terdampak/import_latlng', [App\Http\Controllers\SektorTerdampakController::class, 'import_latlng'])->name('sektor_terdampak.import_latlng');
    Route::get('sektor_terdampak/export/data', [App\Http\Controllers\SektorTerdampakController::class, 'export_data'])->name('sektor_terdampak.export_data');

    Route::prefix('paket_pekerjaan/{paket_pekerjaan}/rincian_pekerjaan')->name('paket_pekerjaan.rincian_pekerjaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\RincianPekerjaanController::class, 'index'])->name('index');
        Route::post('/search', [App\Http\Controllers\RincianPekerjaanController::class, 'search'])->name('search');
        Route::get('/create', [App\Http\Controllers\RincianPekerjaanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\RincianPekerjaanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\RincianPekerjaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\RincianPekerjaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\RincianPekerjaanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('paket_pekerjaan/{paket_pekerjaan}/timeline_pekerjaan')->name('paket_pekerjaan.timeline_pekerjaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\TimelinePekerjaanController::class, 'index'])->name('index');
        Route::post('/search', [App\Http\Controllers\TimelinePekerjaanController::class, 'search'])->name('search');
        Route::get('/create', [App\Http\Controllers\TimelinePekerjaanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TimelinePekerjaanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\TimelinePekerjaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\TimelinePekerjaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\TimelinePekerjaanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('paket_pekerjaan/{paket_pekerjaan}/realisasi_pekerjaan')->name('paket_pekerjaan.realisasi_pekerjaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\RealisasiPekerjaanController::class, 'index'])->name('index');
        Route::post('/search', [App\Http\Controllers\RealisasiPekerjaanController::class, 'search'])->name('search');
        Route::get('/create', [App\Http\Controllers\RealisasiPekerjaanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\RealisasiPekerjaanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\RealisasiPekerjaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\RealisasiPekerjaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\RealisasiPekerjaanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('paket_pekerjaan/{paket_pekerjaan}/pembayaran_pekerjaan')->name('paket_pekerjaan.pembayaran_pekerjaan.')->group(function () {
        Route::get('/', [App\Http\Controllers\PembayaranPekerjaanController::class, 'index'])->name('index');
        Route::post('/search', [App\Http\Controllers\PembayaranPekerjaanController::class, 'search'])->name('search');
        Route::get('/create', [App\Http\Controllers\PembayaranPekerjaanController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PembayaranPekerjaanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\PembayaranPekerjaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\PembayaranPekerjaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\PembayaranPekerjaanController::class, 'destroy'])->name('destroy');
    });

    //import excel paket pekerjaan
    Route::post('paket_pekerjaan/xclimp', [App\Http\Controllers\PaketPekerjaanController::class, 'importxcl'])->name('importxcl');
});

//
//Route::get('test', function () {
//    $data = \Illuminate\Support\Facades\DB::table('temp')->get();
//    foreach ($data as $item) {
//        $wilayah = \App\Models\Wilayah::where('kode', $item->kode_wilayah)->first();
//        if (!empty($wilayah)) {
//            \App\Models\AnggaranDaerah::create([
//                'wilayah_id' => $wilayah->id,
//                'anggaran_2025' => $item->anggaran_2025,
//                'anggaran_2026' => $item->anggaran_2026,
//                'penyesuaian' => $item->penyesuaian,
//            ]);
//        }
//    }
//});
