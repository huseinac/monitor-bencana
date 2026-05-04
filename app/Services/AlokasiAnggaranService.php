<?php

namespace App\Services;

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

}
