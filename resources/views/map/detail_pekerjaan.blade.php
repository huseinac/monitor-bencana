<div class="iframe-close" onclick="$('#paket_pekerjaan_detail').hide()">✕</div>

<div class="d-flex flex-row p-4" style="gap: 12px;">

    <div class="card shadow-sm" style="flex: 1;border-radius: 12px">
        <div class="card-body" style="padding: 12px;">
            <p style="font-size: 10px;margin: 0;">No. {{ $paket_pekerjaan->no_kontrak }}</p>
            <p style="font-size: 12px;margin: 0;font-weight: bold;">{{ $paket_pekerjaan->nama }}</p>
            <p style="font-size: 10px;margin: 0;color: gray;font-weight: bold">{{ format_date($paket_pekerjaan->tanggal_kontrak) }} - {{ format_date($paket_pekerjaan->tanggal_selesai) }}</p>
            <br>
            <div class="progress" role="progressbar" aria-label="Example 20px high" aria-valuenow="{{ $paket_pekerjaan->persentase }}" aria-valuemin="0" aria-valuemax="100" style="height: 20px">
                <div class="progress-bar" style="width: {{ $paket_pekerjaan->persentase }}%"></div>
            </div>
            <br>
            @php
                $menu_items = ['Grafik Pekerjaan', 'Informasi', 'Penyedia', 'Rincian', 'Timeline','Realisasi', 'Pembayaran'];
            @endphp
            <div class="d-flex flex-column vertical-tab" style="gap: 4px;" role="tablist">
                @foreach($menu_items as $item)
                    <button class="btn d-flex align-items-center item-tab {{ $item === 'Grafik Pekerjaan' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#{{ str_slug($item) }}" type="button" role="tab">{{ $item }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div style="flex: 4;">
        <div class="tab-content border-0" id="tab_pekerjaan">
            <div class="tab-pane fade show active" id="grafik-pekerjaan" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <p style="margin: 0;">Realisasi Keuangan</p>
                                <h5 style="margin: 0;">Rp. 0</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <p style="margin: 0;">Realisasi Fisik</p>
                                <h5 style="margin: 0;">-</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <p style="margin: 0;">Realisasi Waktu</p>
                                <h5 style="margin: 0;">-</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <p style="margin: 0;">Grafik Realisasi Terharap Timeline</p>
                        <h5 style="margin: 0;">-</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="informasi" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Informasi Paket Pekerjaan</h5>

                        <table class="table table-row-bordered">
                            <tr>
                                <td style="width: 200px;">Tahun Anggaran</td>
                                <td style="width: 40px">:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->tahun_anggaran }}</td>
                            </tr>
                            <tr>
                                <td>Nama Paket</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->nama_paket }}</td>
                            </tr>
                            <tr>
                                <td>Program</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->nama_program }}</td>
                            </tr>
                            <tr>
                                <td>Kegiatan / Sub</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->nama_kegiatan }} / {{ $paket_pekerjaan->nama_sub_kegiatan }}</td>
                            </tr>
                            <tr>
                                <td>Pagu Dana</td>
                                <td>:</td>
                                <td style="font-weight: bold;">Rp {{ number_format($paket_pekerjaan->pagu_dana, 0, ',', '.') }}</td>
                            </tr>

                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->kategori_paket_pekerjaan->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Indikator</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->indikator->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Wilayah</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->wilayah->parent->parent->nama ?? '-' }} {{ $paket_pekerjaan->wilayah->parent->nama }}, {{ $paket_pekerjaan->wilayah->nama }}</td>
                            </tr>
                        </table>
                        <h5>Informasi Pengadaan</h5>
                        <table class="table table-row-bordered">
                            <tr>
                                <td style="width: 200px;">NIlai Pagu</td>
                                <td style="width: 40px">:</td>
                                <td style="font-weight: bold;">Rp {{ number_format($paket_pekerjaan->nilai_pagu, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Nilai Kontrak</td>
                                <td>:</td>
                                <td style="font-weight: bold;">Rp {{ number_format($paket_pekerjaan->nilai_kontrak, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Nama Rekening</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->nama_rekening }}</td>
                            </tr>

                            <tr>
                                <td>Penyedia</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No. Kontrak</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->no_kontrak }}</td>
                            </tr>
                            <tr>
                                <td>Masa Pelaksanaan</td>
                                <td>:</td>
                                <td style="font-weight: bold;">
                                    {{ format_date($paket_pekerjaan->tanggal_kontrak) }} s/d {{ format_date($paket_pekerjaan->tanggal_selesai) }}
                                </td>
                            </tr>

                            <tr>
                                <td>Wilayah</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->wilayah->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Koordinat</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->latitude }}, {{ $paket_pekerjaan->longitude }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->keterangan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="penyedia" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Informasi Penyedia</h5>

                        <table class="table table-row-bordered">
                            <tr>
                                <td style="width: 200px;">Penyedia</td>
                                <td style="width: 40px">:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->nama ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>NIB</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->nib ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>NPWP</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->npwp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Kontak Person</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->kontak_person ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>No.Telp</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->notelp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{ $paket_pekerjaan->penyedia->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="rincian" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Rincian Paket Pekerjaan</h5>

                        <table class="table table-borderd table-row-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Target</th>
                                <th>Satuan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($no = 1)
                            @foreach(($paket_pekerjaan->list_rincian_pekerjaan ?? []) as $rincian)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $rincian->nama }}</td>
                                    <td>{{ $rincian->target }}</td>
                                    <td>{{ $rincian->satuan }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Rincian Paket Pekerjaan</h5>

                        <table class="table table-borderd table-row-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Target</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($no = 1)
                            @foreach(($paket_pekerjaan->list_rincian_pekerjaan ?? []) as $rincian)
                                @foreach(($rincian->list_timeline_pekerjaan ?? []) as $timeline)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $rincian->nama }}</td>
                                        <td>{{ format_date($timeline->tanggal_awal) }}</td>
                                        <td>{{ format_date($timeline->tanggal_akhir) }}</td>
                                        <td>{{ $timeline->target }} {{ $rincian->satuan }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="realisasi" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Realisasi Paket Pekerjaan</h5>

                        <table class="table table-borderd table-row-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Realisasi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($no = 1)
                            @foreach(($paket_pekerjaan->list_rincian_pekerjaan ?? []) as $rincian)
                                @foreach(($rincian->list_realisasi_pekerjaan ?? []) as $timeline)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $rincian->nama }}</td>
                                        <td>{{ format_date($timeline->tanggal) }}</td>
                                        <td>{{ $timeline->realisasi }} {{ $rincian->satuan }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pembayaran" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5>Pembayaran Paket Pekerjaan</h5>

                        <table class="table table-borderd table-row-bordered">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($no = 1)
                            @foreach(($paket_pekerjaan->list_pembayaran ?? []) as $pembayaran)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ format_date($pembayaran->tanggal) }}</td>
                                    <td class="text-end">{{ format_number($pembayaran->nominal) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
