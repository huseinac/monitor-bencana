<?php

namespace App\Services;

use App\Models\AnggaranDaerah;
use App\Models\AlokasiAnggaran;

class AlokasiAnggaranService extends IoService
{
    public function __construct()
    {
        $this->model = new AlokasiAnggaran();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['anggaran_daerah_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $keterangan = $params['keterangan'] ?? '';
        if ($keterangan !== '') $model = $model->where('keterangan', 'like', '%' . $keterangan . '%');

        return $model;
    }

    public function filter_params($params, $id = '')
    {
        return $this->cleanNumber($params, ['nominal']);
    }

    public function store($params)
    {
        $anggaranDaerah = new AnggaranDaerah();
        $data = $params;

        $usedBudget = $this->model->where('anggaran_daerah_id', $data['anggaran_daerah_id'])->sum('nominal');
        $availableBudget = $anggaranDaerah->selectRaw('(anggaran_2026 + penyesuaian) as availableBudget')->where('ID', $data['anggaran_daerah_id'])->get()->first()->availableBudget;
        
        if (($availableBudget - $usedBudget) - $data['nominal']) {
            // code...
            return 'Gagal menambahkan alokasi, Nominal alokasi melebihin budget tersedia';
        } else {
            return $this->service->store($request->all());
        }
    }
}
