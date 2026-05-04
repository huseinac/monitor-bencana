<?php

namespace App\Services;

use App\Models\RealisasiPekerjaan;

class RealisasiPekerjaanService extends IoService
{
    public function __construct()
    {
        $this->model = new RealisasiPekerjaan();
        $this->sort_by = ['id' => 'asc'];
        $this->filters = ['rincian_pekerjaan_id'];
    }

    public function dynamic_search($model, $params = [])
    {
        $paket_pekerjaan_id = $params['paket_pekerjaan_id'] ?? '';
        if ($paket_pekerjaan_id !== '') $model = $model->whereHas('rincian_pekerjaan', fn($rincian) => $rincian->where('paket_pekerjaan_id', $paket_pekerjaan_id));

        return $model;
    }

    public function filter_params($params, $id = '')
    {
        $params = $this->cleanNumber($params, ['realisasi']);
        return $this->cleanDate($params, ['tanggal']);
    }

}
