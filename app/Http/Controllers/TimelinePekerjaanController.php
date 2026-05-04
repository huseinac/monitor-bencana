<?php

namespace App\Http\Controllers;

use App\Services\PaketPekerjaanService;
use App\Services\RincianPekerjaanService;
use App\Services\TimelinePekerjaanService;
use Illuminate\Http\Request;

class TimelinePekerjaanController extends Controller
{
    protected $timelinePekerjaanService;
    public function __construct()
    {
        $this->timelinePekerjaanService = new TimelinePekerjaanService();

        $rincianPekerjaanService = new RincianPekerjaanService();
        view()->share('list_rincian_pekerjaan', $rincianPekerjaanService->dropdown(['paket_pekerjaan_id' => request()->route()->parameter('paket_pekerjaan')]));
    }

    public function index($paket_pekerjaan_id)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $paket_pekerjaan = $paketPekerjaanService->find($paket_pekerjaan_id);
        return view('app.admin.paket_pekerjaan.timeline_pekerjaan.index', compact('paket_pekerjaan'));
    }

    public function search(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        $timeline_pekerjaans = $this->timelinePekerjaanService->search(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return view('app.admin.paket_pekerjaan.timeline_pekerjaan._table', compact('timeline_pekerjaans'));
    }

    public function create($paket_pekerjaan_id)
    {
        return view('app.admin.paket_pekerjaan.timeline_pekerjaan._form', compact('paket_pekerjaan_id'));
    }

    public function store(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return $this->timelinePekerjaanService->store($request->all());
    }

    public function edit($paket_pekerjaan_id, $id)
    {
        $timeline_pekerjaan = $this->timelinePekerjaanService->find($id);
        return view('app.admin.paket_pekerjaan.timeline_pekerjaan._form', compact('timeline_pekerjaan', 'paket_pekerjaan_id'));
    }

    public function update(Request $request, $paket_pekerjaan_id, $id)
    {
        return $this->timelinePekerjaanService->update($request->all(), $id);
    }

    public function destroy($paket_pekerjaan_id, $id)
    {
        return $this->timelinePekerjaanService->delete($id);
    }
}
