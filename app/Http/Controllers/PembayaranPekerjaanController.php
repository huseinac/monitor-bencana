<?php

namespace App\Http\Controllers;

use App\Services\PaketPekerjaanService;
use App\Services\PembayaranPekerjaanService;
use Illuminate\Http\Request;

class PembayaranPekerjaanController extends Controller
{
    protected $pembayaranPekerjaanService;
    public function __construct()
    {
        $this->pembayaranPekerjaanService = new PembayaranPekerjaanService();
    }

    public function index($paket_pekerjaan_id)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $paket_pekerjaan = $paketPekerjaanService->find($paket_pekerjaan_id);
        return view('app.admin.paket_pekerjaan.pembayaran_pekerjaan.index', compact('paket_pekerjaan'));
    }

    public function search(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        $pembayaran_pekerjaans = $this->pembayaranPekerjaanService->search(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return view('app.admin.paket_pekerjaan.pembayaran_pekerjaan._table', compact('pembayaran_pekerjaans'));
    }

    public function create($paket_pekerjaan_id)
    {
        return view('app.admin.paket_pekerjaan.pembayaran_pekerjaan._form', compact('paket_pekerjaan_id'));
    }

    public function store(Request $request, $paket_pekerjaan_id)
    {
        $request->merge(['paket_pekerjaan_id' => $paket_pekerjaan_id]);
        return $this->pembayaranPekerjaanService->store($request->all());
    }

    public function edit($paket_pekerjaan_id, $id)
    {
        $pembayaran_pekerjaan = $this->pembayaranPekerjaanService->find($id);
        return view('app.admin.paket_pekerjaan.pembayaran_pekerjaan._form', compact('pembayaran_pekerjaan', 'paket_pekerjaan_id'));
    }

    public function update(Request $request, $paket_pekerjaan_id, $id)
    {
        return $this->pembayaranPekerjaanService->update($request->all(), $id);
    }

    public function destroy($paket_pekerjaan_id, $id)
    {
        return $this->pembayaranPekerjaanService->delete($id);
    }
}
