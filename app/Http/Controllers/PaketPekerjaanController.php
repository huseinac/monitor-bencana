<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\IndikatorService;
use App\Services\KategoriPaketPekerjaanService;
use App\Services\PaketPekerjaanService;
use App\Services\PelaksanaService;
use App\Services\PenyediaService;
use App\Services\WilayahService;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PaketPekerjaan;

class PaketPekerjaanController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new PaketPekerjaanService();
        $this->viewPrefix = 'app.admin.paket_pekerjaan';
        $this->itemVariable = 'paket_pekerjaan';

        $wilayahService = new WilayahService();
        $list_provinsi = $wilayahService->search(['kode_in' => ['11', '12', '13']]);
        $list_kabupaten = $wilayahService->search(['parent_kode_in' => $list_provinsi->pluck('kode')->toArray()]);
        $list_kecamatan = $wilayahService->search(['parent_kode_in' => $list_kabupaten->pluck('kode')->toArray()]);

        view()->share('list_provinsi', $list_provinsi);
        view()->share('list_kabupaten', $list_kabupaten);
        view()->share('list_kecamatan', $list_kecamatan);

        $indikatorService = new IndikatorService();
        view()->share('list_indikator', $indikatorService->dropdown(['no_children' => '1', 'with' => ['list_pelaksana']]));
        view()->share('list_indikator_data', $indikatorService->search(['no_children' => '1', 'with' => ['list_pelaksana']]));
        $pelaksanaService = new PelaksanaService();
        view()->share('list_pelaksana', $pelaksanaService->dropdown());

        $kategoriPaketPekerjaanService = new KategoriPaketPekerjaanService();
        view()->share('list_kategori', $kategoriPaketPekerjaanService->dropdown());

        $penyediaService = new PenyediaService();
        view()->share('list_penyedia', $penyediaService->dropdown());
    }

    public function search(Request $request)
    {
        $request->merge([
            'with' => ['pelaksana', 'indikator.parent', 'wilayah.parent.parent', 'kategori_paket_pekerjaan'],
        ]);
        return parent::search($request);
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'paket_pekerjaan');
        if ($filename !== '') $request->merge(['foto' => $filename]);

        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'paket_pekerjaan');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        return parent::store($request);
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_foto', 'paket_pekerjaan');
        if ($filename !== '') $request->merge(['foto' => $filename]);

        $filename = DocumentService::save_file($request, 'file_foto_sesudah', 'paket_pekerjaan');
        if ($filename !== '') $request->merge(['foto_sesudah' => $filename]);

        $latitude = $request->input('latitude') ?? '';
        $longitude = $request->input('longitude') ?? '';
        if ($latitude === '' || $longitude === '') $request = $this->getRandomlatitudeLongitude($request);

        return parent::update($request, $id);
    }

    public function listWilayahExcel($scope, $children = null) {
        // code...
        if (in_array($scope, [2,3]) && empty($children)) {
            // code...
            abort(404);exit;
        }

        $wilayahService = new WilayahService();

        switch ($scope) {
            case 1:
                // code...
                $list_provinsi = $wilayahService->search(['kode_in' => ['11', '12', '13']]);
                return $list_provinsi->toJson();
                break;

            case 2:
                // code...
                if (strpos($children, '.') > 0) {
                    // code...
                    abort(404);exit;
                }
                $list_kabupaten = $wilayahService->search(['parent_kode_in' => [$children]]);
                return $list_kabupaten->toJson();
                break;

            case 3:
                // code...
                $list_kecamatan = $wilayahService->search(['parent_kode_in' => [$children]]);
                return $list_kecamatan->toJson();
                break;
            
            default:
                // code...
                abort(404);
                break;
        }
    }

    public function listIndikatorExcel() {
        // code...
        $indikatorService = new IndikatorService();
        $idk = $indikatorService->search([]);
        return $idk->toJson();
    }

    public function listPelaksanaExcel() {
        // code...
        $pelaksanaService = new PelaksanaService();
        $pelaksana = $pelaksanaService->search([]);
        return $pelaksana->toJson();
    }

    public function kategoriPaketExcel() {
        // code...
        $kategoriPaketPekerjaanService = new KategoriPaketPekerjaanService();
        $katPaket = $kategoriPaketPekerjaanService->search([]);
        return $katPaket->toJson();
    }

    public function daftarPenyediaExcel() {
        // code...
        $penyedia = new PenyediaService();
        $pny = $penyedia->search([]);
        return $pny->toJson();
    }

    //import excel
    public function importxcl(Request $request) {
        // code...
        $pp = new PaketPekerjaan();

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());

        $sheet = $spreadsheet->getSheetByName('XXX');

        if (!$sheet) {
            // code...
            return response()->json([
                'success' => false, 
                'message' => 'Format file salah! Mohon menggunakan file template sistem.'
            ], 422);
        }

        $cek = $sheet->getCell('A1000')->getValue();
        if ($cek !== 'mychemicalromance') {
            // code...
            return response()->json([
                'success' => false, 
                'message' => 'Format file salah! Mohon menggunakan file template sistem.'
            ], 422);
        }

        $data = Excel::toArray(new \App\Imports\GenericImport, $request->file('file'));
        $data = array_slice($data, -1);
        $data = $data[0];
        $data = array_slice($data, 1);
        $data = array_splice($data, 0, -1); //

        $gkBolehKosong = [ //karena fk di db 
            ['wilayah_id', 5], ['indikator_id', 7], ['pelaksana_id', 9], ['penyedia_id', 24]
        ];
        foreach ($gkBolehKosong as $gg) {
            // code...
            foreach ($data as $ee) {
                // code...
                if (empty($ee[$gg[1]])) {
                    // code...
                    return response()->json([
                        'success' => false, 
                        'message' => 'Kolom '.$gg[0].' tidak boleh kosong !.'
                    ], 422);
                }
            }
        }

        $a = 0;
        foreach ($data as $x) {
            // code...
            if (is_null($x[0])) { continue; }

            $insertData[$a] = [
                'wilayah_id' => $x[5],
                'indikator_id' => $x[7],
                'pelaksana_id' => $x[9],
                'nama' => ($x[12] ?? null),
                'nominal' => ($x[13] ?? null),
                'keterangan' => ($x[16] ?? null),
                'latitude' => ($x[14] ?? null),
                'longitude' => ($x[15] ?? null),
                'kategori_paket_pekerjaan_id' => $x[11],
                'penyedia_id' => $x[24],
                'tahun_anggaran' => ($x[17] ?? null),
                'nama_program' => ($x[18] ?? null),
                'nama_kegiatan' => ($x[19] ?? null),
                'nama_sub_kegiatan' => ($x[20] ?? null),
                'nama_rekening' => ($x[21] ?? null),
                'pagu_dana' => ($x[22] ?? null),
                'no_kontrak' => ($x[25] ?? null),
                'nama_paket' => ($x[26] ?? null),
                'jenis_pengadaan' => ($x[27] ?? null),
                'model_pengadaan' => ($x[28] ?? null),
                'tanggal_kontrak' => (empty($x[29]) ? null : Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($x[29]))->format('Y-m-d')),
                'tanggal_selesai' => (empty($x[30]) ? null : Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($x[30]))->format('Y-m-d')),
                'nilai_pagu' => ($x[31] ?? null),
                'nilai_kontrak' => ($x[32] ?? null),
            ];
            $a += 1;
        }
        // echo json_encode($insertData, JSON_PRETTY_PRINT);exit;
        $ist = PaketPekerjaan::insert($insertData);
        if ($ist) {
            // code...
            return response()->json([
                'success' => true,
                'message' => 'Paket Pekerjaan berhasil ditambahkan'
            ], 200);
        } else {
            return response()->json([
                'success' => false, 
                'message' => 'Data gagal disimpan.'
            ], 200);
        }
    }
}
