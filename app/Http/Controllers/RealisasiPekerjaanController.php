<?php

namespace App\Http\Controllers;

use App\Services\PaketPekerjaanService;
use App\Services\RincianPekerjaanService;
use App\Services\RealisasiPekerjaanService;
use Illuminate\Http\Request;

class RealisasiPekerjaanController extends Controller
{
    protected $realisasiPekerjaanService;
    public function __construct()
    {
        $this->realisasiPekerjaanService = new RealisasiPekerjaanService();

        $rincianPekerjaanService = new RincianPekerjaanService();
        view()->share('list_rincian_pekerjaan', $rincianPekerjaanService->dropdown(['paket_pekerjaan_id' => request()->route()->parameter('paket_pekerjaan')]));
    }

    public function index($paket_pekerjaan_id)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $paket_pekerjaan = $paketPekerjaanService->find($paket_pekerjaan_id);
        return view('app.admin.paket_pekerjaan.realisasi_pekerjaan.index', compact('paket_pekerjaan'));
    }

    public function search(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        $realisasi_pekerjaans = $this->realisasiPekerjaanService->search(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return view('app.admin.paket_pekerjaan.realisasi_pekerjaan._table', compact('realisasi_pekerjaans'));
    }

    public function create($paket_pekerjaan_id)
    {
        return view('app.admin.paket_pekerjaan.realisasi_pekerjaan._form', compact('paket_pekerjaan_id'));
    }

    public function store(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return $this->realisasiPekerjaanService->store($request->all());
    }

    public function edit($paket_pekerjaan_id, $id)
    {
        $realisasi_pekerjaan = $this->realisasiPekerjaanService->find($id);
        return view('app.admin.paket_pekerjaan.realisasi_pekerjaan._form', compact('realisasi_pekerjaan', 'paket_pekerjaan_id'));
    }

    public function update(Request $request, $paket_pekerjaan_id, $id)
    {
        return $this->realisasiPekerjaanService->update($request->all(), $id);
    }

    public function destroy($paket_pekerjaan_id, $id)
    {
        return $this->realisasiPekerjaanService->delete($id);
    }
}
