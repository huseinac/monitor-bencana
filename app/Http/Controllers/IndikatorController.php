<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use App\Services\IndikatorService;
use App\Services\PelaksanaIndikatorService;
use App\Services\PelaksanaService;
use Illuminate\Http\Request;

class IndikatorController extends IoResourceController
{
    public function __construct()
    {
        $this->service = new IndikatorService();
        $this->viewPrefix = 'app.admin.indikator';
        $this->itemVariable = 'indikator';

        $pelaksanaService = new PelaksanaService();
        view()->share('list_pelaksana', $pelaksanaService->dropdown());
    }

    public function store(Request $request)
    {
        $filename = DocumentService::save_file($request, 'file_icon', 'indikator');
        if ($filename !== '') $request->merge(['icon' => $filename]);
        $filename = DocumentService::save_file($request, 'file_icon2', 'indikator');
        if ($filename !== '') $request->merge(['icon2' => $filename]);
        $filename = DocumentService::save_file($request, 'file_icon3', 'indikator');
        if ($filename !== '') $request->merge(['icon3' => $filename]);

        $indikator = parent::store($request);

        if (($request->input('pelaksana_id') ?? '') !== '') {
            $indikator->list_pelaksana()->create(['pelaksana_id' => $request->input('pelaksana_id')]);
        }

        return $indikator;
    }

    public function update(Request $request, $id)
    {
        $filename = DocumentService::save_file($request, 'file_icon', 'indikator');
        if ($filename !== '') $request->merge(['icon' => $filename]);
        $filename = DocumentService::save_file($request, 'file_icon2', 'indikator');
        if ($filename !== '') $request->merge(['icon2' => $filename]);
        $filename = DocumentService::save_file($request, 'file_icon3', 'indikator');
        if ($filename !== '') $request->merge(['icon3' => $filename]);

        $indikator = parent::update($request, $id);

        $pelaksanaIndikatorService = new PelaksanaIndikatorService();
        if ($indikator->list_pelaksana->count() > 0) {
            if (($request->input('pelaksana_id') ?? '') !== '') {
                $pelaksanaIndikatorService->update(['pelaksana_id' => $request->input('pelaksana_id')], $indikator->list_pelaksana[0]->id);
            } else {
                $pelaksanaIndikatorService->delete($indikator->list_pelaksana[0]->id);
            }
        } else {
            if (($request->input('pelaksana_id') ?? '') !== '') {
                $indikator->list_pelaksana()->create(['pelaksana_id' => $request->input('pelaksana_id')]);
            }
        }

        return $indikator;
    }

}
