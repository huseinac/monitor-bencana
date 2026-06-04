<?php

namespace App\Services;

use App\Models\PaketPekerjaan;

class PaketPekerjaanService extends IoService
{
    public function __construct()
    {
        $this->model = new PaketPekerjaan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = [
            'indikator_id', 
            'wilayah_id', 
            'pelaksana_id', 
            'tahun_anggaran',
            'status_anggaran_id',
            'status_pelaksanaan_id',
        ];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        $kabupaten_id = $params['kabupaten_id'] ?? '';
        if ($kabupaten_id !== '') $model = $model->whereHas('wilayah.parent', fn($parent) => $parent->where('id', $kabupaten_id));

        $provinsi_id = $params['provinsi_id'] ?? '';
        if ($provinsi_id !== '') $model = $model->whereHas('wilayah.parent.parent', fn($parent) => $parent->where('id', $provinsi_id));

        return $model;
    }

    public function dropdown($params = []): array
    {
        $result = [];
        foreach ($this->search($params) as $item) $result[$item->id] = $item->indikator->nama . '; ' . $item->wilayah->parent->parent->nama . ' - ' . $item->wilayah->parent->nama . ' - ' . $item->wilayah->nama;
        return $result;
    }

    public function filter_params($params, $id = '')
    {
        $params = $this->cleanDate($params, ['tanggal_kontrak', 'tanggal_selesai']);
        return $this->cleanNumber($params, ['nominal', 'pagu_dana', 'nilai_pagu', 'nilai_kontrak']);
    }

}
