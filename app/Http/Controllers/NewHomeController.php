<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewHomeController extends Controller
{
    public function index()
    {
        $buttons = [
            ['icon' => 'images/icons/wilayah.png', 'alt' => 'wilayah', 'label' => 'Wilayah'],
            ['icon' => 'images/icons/indikator.png', 'alt' => 'indikator', 'label' => 'Update Kondisi<br>(indikator)'],
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

        return view('new_map.index', compact('buttons', 'panels'));
    }
}
