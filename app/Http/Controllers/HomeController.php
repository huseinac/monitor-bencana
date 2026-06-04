<?php

namespace App\Http\Controllers;

use App\Models\AnggaranDaerah;
use App\Models\Indikator;
use App\Models\MasalahKritis;
use App\Models\PaketPekerjaan;
use App\Models\Pelaksana;
use App\Models\SektorTerdampak;
use App\Models\Wilayah;
use App\Services\AnggaranDaerahService;
use App\Services\MasalahKritisService;
use App\Services\PaketPekerjaanService;
use App\Services\SektorTerdampakService;
use App\Services\PelaksanaService;
use App\Services\StatusAnggaranService;
use App\Services\StatusPelaksanaanService;
use App\Services\WilayahService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function get_wilayah(Request $request)
    {
        $parent_kode = $request->input('kode') ?? '';
        $cache_key = 'wilayah_' . ($parent_kode === '' ? 'root' : $parent_kode);

        return Cache::remember($cache_key, now()->addDay(7), function () use ($parent_kode) {
            $params_wilayah['parent_kode'] = $parent_kode;
            if ($parent_kode === '') $params_wilayah['kode_in'] = ['11', '12', '13'];

            $wilayahService = new WilayahService();
            $list_wilayah = $wilayahService->search($params_wilayah);
            $list_sektor_terdampak = SektorTerdampak::whereHas('wilayah', fn($wilayah) => $wilayah->whereIn(DB::raw('LEFT(kode, 2)'), ['11', '12', '13']))->get();

            foreach ($list_wilayah as $wilayah) {
                $sektor = $list_sektor_terdampak->filter(fn($item) => str_starts_with($item->wilayah->kode, $wilayah->kode));

                if ($wilayah->kondisi != '') {
                    $persentase = 0;
                    if ($wilayah->kondisi == 'Normal') $persentase = 100;
                    if ($wilayah->kondisi == 'Mendekati') $persentase = 70;

                    $wilayah->persentase = $persentase;
                    $wilayah->warna = $this->get_warna($wilayah->persentase);
                } else {
                    $count_normal = $sektor->where('status', 'Normal')->count();
                    $count_mendekati = $sektor->where('status', 'Mendekati')->count();
                    $count_atensi = $sektor->where('status', 'Atensi')->count();
                    $total_sektor = $sektor->count();

                    if ($total_sektor > 0) {
                        $weighted_score = ($count_normal * 1) + ($count_mendekati * 0.7) + ($count_atensi * 0.3);
                        $persentase = ($weighted_score / $total_sektor) * 100;
                    } else $persentase = 100;
                    $wilayah->persentase = round($persentase, 2);
                    $wilayah->warna = $this->get_warna($wilayah->persentase);
                }

                if (strlen($wilayah->kode) == 2) {
                    $wilayah->warna = '#ffffff';
                }
            }

            return $list_wilayah;
        });
    }

    public function get_wilayah_all(Request $request)
    {
        $wilayahService = new WilayahService();
        $list_provinsi = $wilayahService->search();
        return $list_provinsi->toJson();
    }

    private function get_warna($persentase) {
        if ($persentase <= 30) return "#F9A825";
        if ($persentase <= 70) return "#0D47A1";
        return "#1B5E20";
    }

    public function get_indikator(Request $request)
    {
        $kode_wilayah = $request->input('wilayah_kode') ?? '';
        $cache_key = 'indikator_' . ($kode_wilayah === '' ? 'root' : $kode_wilayah);

        return Cache::remember($cache_key, now()->addDays(7), function () use ($kode_wilayah) {
            $list_sektor_terdampak = SektorTerdampak::whereHas('wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%'))
                ->with(['indikator', 'indikator.list_pelaksana.pelaksana', 'wilayah.parent.parent'])
                ->where('latitude', '<>', '')
                ->where('longitude', '<>', '')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
            foreach ($list_sektor_terdampak as $sektor) {
                $persentase = 30;
                if ($sektor->status == 'Mendekati') $persentase = 70;
                if ($sektor->status == 'Normal') $persentase = 100;
                $sektor->persentase = $persentase;
            }

            $list_indikator = Indikator::orderBy('kode')->get();
            foreach ($list_indikator as $indikator) {
                $sektor = $list_sektor_terdampak->where('indikator_id', $indikator->id);
                $indikator->list_sektor_terdampak = $sektor->values();
                $indikator->total_data = $sektor->count();

                $count_normal = $sektor->where('status', 'Normal')->count();
                $count_mendekati = $sektor->where('status', 'Mendekati')->count();
                $count_atensi = $sektor->where('status', 'Atensi')->count();
                $indikator->total_normal = $count_normal;
                $indikator->total_mendekati = $count_mendekati;
                $indikator->total_atensi = $count_atensi;

                $indikator->count_status = [
                    'normal' => $count_normal,
                    'mendekati' => $count_mendekati,
                    'atensi' => $count_atensi,
                ];
                $indikator->count_kondisi = [
                    'RR' => $sektor->where('kondisi', 'RR')->count(),
                    'RS' => $sektor->where('kondisi', 'RS')->count(),
                    'RB' => $sektor->where('kondisi', 'RB')->count(),
                ];

                $total_sektor = $sektor->count();
                if ($total_sektor > 0) {
                    $weighted_score = ($count_normal * 1) + ($count_mendekati * 0.7) + ($count_atensi * 0.3);
                    $persentase = ($weighted_score / $total_sektor) * 100;
                } else $persentase = 100;
                $indikator->persentase = round($persentase, 2);
            }

            $list_pelaksana = Pelaksana::orderBy('nama')->get();
            foreach ($list_pelaksana as $pelaksana) {
                $sektor = $list_sektor_terdampak->filter(function ($item) use ($pelaksana) {
                    return $item->indikator
                        && $item->indikator->list_pelaksana->isNotEmpty()
                        && $item->indikator->list_pelaksana->contains('pelaksana_id', $pelaksana->id);
                });

                $pelaksana->list_sektor_terdampak = $sektor->values();

                $count_normal = $sektor->where('status', 'Normal')->count();
                $count_mendekati = $sektor->where('status', 'Mendekati')->count();
                $count_atensi = $sektor->where('status', 'Atensi')->count();

                $pelaksana->count_status = [
                    'normal' => $count_normal,
                    'mendekati' => $count_mendekati,
                    'atensi' => $count_atensi,
                ];
                $pelaksana->count_kondisi = [
                    'RR' => $sektor->where('kondisi', 'RR')->count(),
                    'RS' => $sektor->where('kondisi', 'RS')->count(),
                    'RB' => $sektor->where('kondisi', 'RB')->count(),
                ];

                $total_sektor = $sektor->count();
                if ($total_sektor > 0) {
                    $weighted_score = ($count_normal * 1) + ($count_mendekati * 0.7) + ($count_atensi * 0.3);
                    $persentase = ($weighted_score / $total_sektor) * 100;
                } else $persentase = 100;
                $pelaksana->persentase = round($persentase, 2);
            }

//            $list_masalah_kritis = MasalahKritis::whereHas('wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%'))
//                ->with(['pelaksana', 'indikator', 'wilayah.parent.parent'])
//                ->get();

            return response()->json([
                'list_indikator' => $list_indikator,
                'list_pelaksana' => $list_pelaksana,
                'list_masalah_kritis' => [], //$list_masalah_kritis,
            ]);
        });
    }

    public function get_pekerjaan(Request $request)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $kode_wilayah = $request->input('wilayah_kode') ?? '';
        $tahun_anggaran = $request->input('tahun_anggaran') ?? false;
        $pelaksana_id = $request->input('pelaksana_id') ?? false;
        $status_anggaran_id = $request->input('status_anggaran_id') ?? false;
        $status_pelaksanaan_id = $request->input('status_pelaksanaan_id') ?? false;
        $cache_key = 'pekerjaan_' . ($kode_wilayah === '' ? 'root' : $kode_wilayah);

        $list_pelaksana = Pelaksana::orderBy('nama');
        !$pelaksana_id ?: $list_pelaksana->where('id', $pelaksana_id);
        $list_pelaksana = $list_pelaksana->get();

        $list_paket_pekerjaan = PaketPekerjaan::whereHas('wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%'))
            ->with(['indikator', 'pelaksana', 'kategori_paket_pekerjaan', 'penyedia', 'list_rincian_pekerjaan.list_realisasi_pekerjaan', 'wilayah.parent.parent']);

        !$tahun_anggaran ?: $list_paket_pekerjaan->where('tahun_anggaran', $tahun_anggaran);
        !$status_anggaran_id ?: $list_paket_pekerjaan->where('status_anggaran_id', $status_anggaran_id);
        !$status_pelaksanaan_id ?: $list_paket_pekerjaan->where('status_pelaksanaan_id', $status_pelaksanaan_id);

        $list_paket_pekerjaan = $list_paket_pekerjaan->get();

        foreach ($list_paket_pekerjaan as $pekerjaan) {
            $rincian = $pekerjaan->list_rincian_pekerjaan;
            $pekerjaan->persentase = $rincian->isNotEmpty()
                ? round($rincian->avg(fn($r) => $r->list_realisasi_pekerjaan->max('realisasi')) * 100)
                : 0;
        }

        foreach ($list_pelaksana as $pelaksana) {
            $pekerjaan = $list_paket_pekerjaan->where('pelaksana_id', $pelaksana->id);
            $pelaksana->list_pekerjaan = $pekerjaan->values();
            $pelaksana->persentase = $pekerjaan->isNotEmpty() ? round($pekerjaan->avg('persentase')) : 100;
        }

        return response()->json($list_pelaksana);

//         return Cache::remember($cache_key, now()->addDays(7), function () use ($kode_wilayah) {
//             $list_pelaksana = Pelaksana::orderBy('nama')->get();
//             $list_paket_pekerjaan = PaketPekerjaan::whereHas('wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%'))
//                 ->with(['indikator', 'pelaksana', 'kategori_paket_pekerjaan', 'penyedia', 'list_rincian_pekerjaan.list_realisasi_pekerjaan', 'wilayah.parent.parent'])
// //                ->where('latitude', '<>', '')
// //                ->where('longitude', '<>', '')
// //                ->whereNotNull('latitude')
// //                ->whereNotNull('longitude')
//                 ->get();

//             foreach ($list_paket_pekerjaan as $pekerjaan) {
//                 $rincian = $pekerjaan->list_rincian_pekerjaan;
//                 $pekerjaan->persentase = $rincian->isNotEmpty()
//                     ? round($rincian->avg(fn($r) => $r->list_realisasi_pekerjaan->max('realisasi')) * 100)
//                     : 0;
//             }

//             foreach ($list_pelaksana as $pelaksana) {
//                 $pekerjaan = $list_paket_pekerjaan->where('pelaksana_id', $pelaksana->id);
//                 $pelaksana->list_pekerjaan = $pekerjaan->values();
//                 $pelaksana->persentase = $pekerjaan->isNotEmpty() ? round($pekerjaan->avg('persentase')) : 100;
//             }

//             return response()->json($list_pelaksana);
//         });
    }

    public function get_anggaran(Request $request)
    {

        $kode_wilayah = $request->input('wilayah_kode') ?? '';
        $cache_key = 'anggaran_' . ($kode_wilayah === '' ? 'root' : $kode_wilayah);
        $jsonResponse = collect();

        // return Cache::remember($cache_key, now()->addDays(3), function () use ($kode_wilayah) {
        //     $list_anggaran = AnggaranDaerah::whereHas('wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%'))
        //         ->with(['list_alokasi', 'wilayah.parent.parent'])
        //         ->get();
        //     dd($list_anggaran->toRawSql());exit;

        //     return response()->json($list_anggaran);
        // });

        if (empty($kode_wilayah)) {
            // code...
            $y = AnggaranDaerah::whereHas(
                    'wilayah', fn($wilayah) => $wilayah->whereNull('parent_kode')
                )
                ->with(['list_alokasi', 'wilayah.parent.parent'])
                ->get()
            ;
            foreach ($y as $x) {
                // code...
                $list_anggaran_provinsi = AnggaranDaerah::select('*')
                    ->selectRaw("1 as is_provinsi")
                    ->where(
                        'wilayah_id', $x->wilayah_id
                    )
                    ->with(['list_alokasi', 'wilayah.parent.parent'])
                    ->get()
                ;
                $jsonResponse = $jsonResponse->concat($list_anggaran_provinsi);

                $list_anggaran = AnggaranDaerah::select('*')
                    ->selectRaw("0 as is_provinsi")
                    ->whereHas(
                        'wilayah', fn($wilayah) => $wilayah->where('parent_kode', '=', function ($qry) use($x) {
                            // code...
                            return $qry->select("kode")->from('wilayah')->where('id', $x->wilayah_id);
                        })
                    )
                    ->with(['list_alokasi', 'wilayah.parent.parent'])
                    ->get()
                ;

                $jsonResponse = $jsonResponse->concat($list_anggaran);
            }
        } else {
            $y = AnggaranDaerah::whereHas(
                    'wilayah', fn($wilayah) => $wilayah->where('kode', 'like', $kode_wilayah . '%')
                )
                ->with(['list_alokasi', 'wilayah.parent.parent'])
                ->get()
            ;
            $jsonResponse = $jsonResponse->concat($y);
        }


        return response()->json($jsonResponse);
    }

    public function detail_pekerjaan($id)
    {
        $paketPekerjaanService = new PaketPekerjaanService();
        $paket_pekerjaan = $paketPekerjaanService->find($id);
        $paket_pekerjaan->list_rincian_pekerjaan;
        $rincian = $paket_pekerjaan->list_rincian_pekerjaan;
        $paket_pekerjaan->rincian_pekerjaan;
        $paket_pekerjaan->persentase = $rincian->isNotEmpty()
            ? round($rincian->avg(fn($r) => $r->list_realisasi_pekerjaan->max('realisasi')) * 100)
            : 0;

        return view('map.detail_pekerjaan', compact('paket_pekerjaan'));
    }


    public function hapus_cache($kode = '')
    {
        $suffix = $kode === '' ? 'root' : $kode;
        Cache::forget("wilayah_{$suffix}");
        Cache::forget("indikator_{$suffix}");
        Cache::forget("pekerjaan_{$suffix}");
        Cache::forget("anggaran_{$suffix}");

        $kodes = ['11', '12', '13'];

        $level1 = Wilayah::whereIn('kode', $kodes)->pluck('kode')->toArray();
        $level2 = Wilayah::whereIn('parent_kode', $level1)->pluck('kode')->toArray();
        $level3 = Wilayah::whereIn('parent_kode', $level2)->pluck('kode')->toArray();

        $allKodes = array_merge($level1, $level2, $level3);

        // Forget all caches
        foreach ($allKodes as $wilayahKode) {
            Cache::forget("wilayah_{$wilayahKode}");
            Cache::forget("indikator_{$wilayahKode}");
            Cache::forget("pekerjaan_{$wilayahKode}");
            Cache::forget("anggaran_{$wilayahKode}");
        }

        return "Berhasil menghapus cache";
    }


    public function map()
    {
        $pelaksanaService = new PelaksanaService();
        view()->share('list_pelaksana', $pelaksanaService->dropdown());

        $statusAnggaranService = new StatusAnggaranService();
        view()->share('list_status_anggaran', $statusAnggaranService->dropdown());

        $statusPelaksanaanService = new StatusPelaksanaanService();
        view()->share('list_status_pelaksanaan', $statusPelaksanaanService->dropdown());

        $buttons = [
            ['icon' => 'images/icons/wilayah.png', 'alt' => 'wilayah', 'label' => 'Wilayah'],
            ['icon' => 'images/icons/indikator.png', 'alt' => 'indikator', 'label' => 'Update Kondisi<br>(indikator)'],
//            ['icon' => 'images/icons/pelaksana.png', 'alt' => 'pelaksana',   'label' => 'K/L<br>Pelaksana'],
            ['icon' => 'images/icons/pekerjaan.png', 'alt' => 'pekerjaan', 'label' => 'DalRenduk'],
            ['icon' => 'images/icons/tkd.png', 'alt' => 'tkd', 'label' => 'TKD'],
        ];
        $panels = [
            ['id' => 'wilayah', 'title' => 'Kondisi dan Progress Indikator Pemulihan Pemerintahan dan Kemasyarakatan yang Terdampak Bencana', 'sub' => '', 'content_id' => 'list_item_wilayah'],
            ['id' => 'indikator', 'title' => 'Kondisi dan Progress Indikator Pemulihan Pemerintahan dan Kemasyarakatan yang Terdampak Bencana', 'sub' => '', 'content_id' => 'list_item_indikator'],
            ['id' => 'pelaksana', 'title' => 'Kondisi Kinerja Kementerian Penanggung Jawab Percepatan Penanganan Bencana Sumatera dan Aceh',   'sub' => '', 'content_id' => 'list_item_pelaksana'],
            ['id' => 'pekerjaan', 'title' => 'Ringkasan Paket Pekerjaan Penanganan Bencana Sumatra dan Aceh', 'sub' => 'Ringkasan jenis paket pekerjaan terkait penanganan bencana di Sumatera dan Aceh', 'content_id' => 'list_item_pekerjaan'],
            ['id' => 'tkd', 'title' => 'Analisa Penyaluran Transfer Ke Daerah (TKD ) PROV dan Kabupaten/Kota Terdampak Bencana Sumatera dan Aceh', 'sub' => '', 'content_id' => 'list_item_tkd'],
        ];

        return view('map.index', compact('buttons', 'panels'));
    }
}
