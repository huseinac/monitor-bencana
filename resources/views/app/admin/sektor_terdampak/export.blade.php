<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <td colspan="13" style="background-color: #DEEAF1; color: #1F4E79; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle;">
            REKAPITULASI LOKASI TERDAMPAK BENCANA BERDASARKAN WILAYAH
        </td>
    </tr>
    <tr>
        <td colspan="13" style="color: #595959; font-size: 9pt; text-align: center; vertical-align: middle;">
            Sumber Data: Sistem Informasi GeoPass SatGASPRR | https://geopas.satgasprr.go.id/
        </td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan="13" style="color: #595959; font-size: 9pt; text-align: left; vertical-align: middle;">
            Tanggal cetak : {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </td>
    </tr>
    <tr>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">No.</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Nama Lokasi Terdampak</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Indikator</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Alamat</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Nama Propinsi</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Nama Kabupaten/Kota</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Nama Kecamatan</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Nama Desa</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Latitude</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Longitude</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Kondisi Awal</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Kondisi</th>
        <th style="background-color: #0070C0; color: #FFFFFF; text-align: center; vertical-align: middle; font-size: 11pt;">Status Update Kondisi</th>
    </tr>
    </thead>
    <tbody>
    @forelse($sektor_terdampaks as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_lokasi }}</td>
            <td>{{ $item->indikator->nama }}</td>
            <td>{{ $item->alamat }}</td>
            <td>{{ $item->wilayah->parent->parent->nama }}</td>
            <td>{{ $item->wilayah->parent->nama }}</td>
            <td>{{ $item->wilayah->nama }}</td>
            <td></td>
            <td>{{ $item->latitude }}</td>
            <td>{{ $item->longitude }}</td>
            <td>{{ $item->kondisi_awal }}</td>
            <td>{{ $item->kondisi }}</td>
            <td>{{ $item->status }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="13" style="text-align: center;">Tidak ada data</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
