<?php

namespace App\Services;

use App\Models\SektorTerdampak;

class SektorTerdampakService extends IoService
{
    public array $list_kondisi = SektorTerdampak::LIST_KONDISI;
    public function __construct()
    {
        $this->model = new SektorTerdampak();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['indikator_id', 'wilayah_id', 'pelaksana_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        $nama_lokasi = $params['nama_lokasi'] ?? '';
        if ($nama_lokasi !== '') $model = $model->where('nama_lokasi', 'like', '%' . $nama_lokasi . '%');

        $kabupaten_id = $params['kabupaten_id'] ?? '';
        if ($kabupaten_id !== '') $model = $model->whereHas('wilayah.parent', fn($parent) => $parent->where('id', $kabupaten_id));

        $provinsi_id = $params['provinsi_id'] ?? '';
        if ($provinsi_id !== '') $model = $model->whereHas('wilayah.parent.parent', fn($parent) => $parent->where('id', $provinsi_id));

        return $model;
    }

    public function dropdown($params = []): array
    {
        $result = [];
        foreach ($this->search($params) as $item) $result[$item->id] = $item->nama_lokasi . '; ' . $item->indikator->nama . '; ' . $item->wilayah->parent->nama . ' - ' . $item->wilayah->nama;
        return $result;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanDate($params, ['batas_waktu']);
    }

}
