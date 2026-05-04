<?php

namespace App\Services;

use App\Models\User;

class UserService extends IoService
{
    public array $list_akses = User::LIST_AKSES;

    public function __construct()
    {
        $this->model = new User();
        $this->sort_by = ['nama' => 'asc'];
        $this->filters = ['akses', 'email'];
    }

    public function dynamic_search($model, $params = [])
    {
        $nama = $params['nama'] ?? '';
        if ($nama !== '') $model = $model->where('nama', 'like', '%' . $nama . '%');

        $akses_in = $params['akses_in'] ?? '';
        if ($akses_in !== '') $model = $model->whereIn('akses', $akses_in);

        return $model;
    }

    public function filter_params($params, $id = '')
    {
        $password = $params['password'] ?? '';
        if ($password !== '') $params['password'] = bcrypt($password);
        else unset($params['password']);

        return $params;
    }

}
