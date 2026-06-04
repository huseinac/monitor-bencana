<?php

namespace App\Services;

use Illuminate\Support\Number;
use App\Models\AnggaranDaerah;
use App\Models\Wilayah;

use Illuminate\Support\Facades\DB;

class AnggaranDaerahService extends IoService
{
    public function __construct()
    {
        $this->model = new AnggaranDaerah();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['wilayah_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        $provinsi_id = $params['provinsi_id'] ?? '';
        if ($provinsi_id !== '') $model = $model->whereHas('wilayah.parent', fn($parent) => $parent->where('id', $provinsi_id));

        return $model;
    }

    public function dropdown($params = []): array
    {
        $params['with'] = ['wilayah'];
        $result = [];
        // foreach ($this->search($params) as $value) $result[$value->id] = $value->wilayah->nama . ' - ' . $value->wilayah->parent->nama;
        foreach ($this->search($params) as $value) $result[$value->id] = $value->wilayah->nama . ' - ' .
            (is_null($value->wilayah->parent) ? $value->wilayah->nama : $value->wilayah->parent->nama) . ' - ' .
            'Rp '. Number::format(($value->anggaran_2026 + $value->penyesuaian), locale: 'id')
        ;
        return $result;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanNumber($params, ['anggaran_2025', 'anggaran_2026', 'penyesuaian']);
    }

    public function store($params)
    {
        $params = $this->filter_params($params);
        
        //cek apakah anggaran provinsi
        $isAnggaranProvinsi = $params['is_anggaran_provinsi'] ?? false;
        if ($isAnggaranProvinsi) {
            // code...
            $wilayah = new Wilayah();
            $x = $wilayah->where('kode', $params['provinsi_id'])->first();
            $params['wilayah_id'] = $x->id;
            $params['provinsi_id'] = $x->id;
        }

        return $this->model->create($params);
    }
}
