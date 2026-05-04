<?php

namespace App\Imports;

use App\Models\ErrorImport;
use App\Models\SektorTerdampak;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SektorTerdampakImport implements ToModel, WithStartRow
{
    public $new_directory, $kabupaten, $list_indikator, $list_kecamatan;
    public function __construct($new_directory, $kabupaten, $list_indikator, $list_kecamatan)
    {
        $this->new_directory = $new_directory;
        $this->kabupaten = $kabupaten;
        $this->list_indikator = $list_indikator;
        $this->list_kecamatan = $list_kecamatan;
    }

    public function model(array $row)
    {

        $nama_lokasi = $row[1];
        $indikator = trim($row[2]);
        $kecamatan = trim($row[3]);
        $alamat = $row[4];
        $latitude = $row[5];
        $longitude = $row[6];
        $kondisi_awal = $row[7];
        $kondisi = $row[8];
        $status = $row[9];
        $keterangan = $row[10];
        $foto_sebelum = $row[11];
        $foto_sesusah = $row[12];
//        dd($indikator, $kecamatan);

        if ($indikator !== '' && $kecamatan !== '') {
            $check_indikator = $this->list_indikator->filter(function ($item) use ($indikator) {
                return strcasecmp($item->nama, $indikator) === 0;
            })->first();
            $check_kecamatan = $this->list_kecamatan->filter(function ($item) use ($kecamatan) {
                return strcasecmp($item->nama, $kecamatan) === 0;
            })->first();
            $list_error = session('list_error', []);
            if (!empty($check_indikator) && !empty($check_kecamatan)) {
                SektorTerdampak::updateOrCreate([
                    'wilayah_id' => $check_kecamatan->id,
                    'indikator_id' => $check_indikator->id,
                    'nama_lokasi' => $nama_lokasi,
                ], [
                    'kondisi_awal' => $kondisi_awal,
                    'foto_sebelum' => $this->new_directory . '/' . $foto_sebelum,
                    'foto_sesudah' => $this->new_directory . '/' . $foto_sesusah,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'kondisi' => $kondisi,
                    'alamat' => $alamat,
                ]);
            } else {
                $penyebab = '';
                if (empty($check_kecamatan)) $penyebab .= 'Kecamatan Tidak Ditemukan' . ' ';
                if (empty($check_indikator)) $penyebab .= 'Indikator Tidak Ditemukan' . ' ';

                $list_error[] = [
                    'baris' => $row,
                    'penyebab' => $penyebab,
                ];
                ErrorImport::create([
                    'kecamatan' => $this->kabupaten->id,
                    'penyebab' => $penyebab,
                    'data' => json_encode($row)
                ]);
            }
            session(['list_error' => $list_error]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
