<?php

namespace App\Http\Controllers;

use App\Services\PaketPekerjaanService;
use App\Services\RincianPekerjaanService;
use Illuminate\Http\Request;

class RincianPekerjaanController extends Controller
{
    protected $rincianPekerjaanService;
    public function __construct()
    {
        $this->rincianPekerjaanService = new RincianPekerjaanService();
    }

    public function index($paket_pekerjaan_id)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $paket_pekerjaan = $paketPekerjaanService->find($paket_pekerjaan_id);
        return view('app.admin.paket_pekerjaan.rincian_pekerjaan.index', compact('paket_pekerjaan'));
    }

    public function search(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        $rincian_pekerjaans = $this->rincianPekerjaanService->search(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return view('app.admin.paket_pekerjaan.rincian_pekerjaan._table', compact('rincian_pekerjaans'));
    }

    public function create($paket_pekerjaan_id)
    {
        return view('app.admin.paket_pekerjaan.rincian_pekerjaan._form', compact('paket_pekerjaan_id'));
    }

    public function store(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return $this->rincianPekerjaanService->store($request->all());
    }

    public function edit($paket_pekerjaan_id, $id)
    {
        $rincian_pekerjaan = $this->rincianPekerjaanService->find($id);
        return view('app.admin.paket_pekerjaan.rincian_pekerjaan._form', compact('rincian_pekerjaan', 'paket_pekerjaan_id'));
    }

    public function update(Request $request, $paket_pekerjaan_id, $id)
    {
        return $this->rincianPekerjaanService->update($request->all(), $id);
    }

    public function destroy($paket_pekerjaan_id, $id)
    {
        return $this->rincianPekerjaanService->delete($id);
    }
}
