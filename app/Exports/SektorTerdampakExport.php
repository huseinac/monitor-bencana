<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SektorTerdampakExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $sektor_terdampaks;

    public function __construct($sektor_terdampaks)
    {
        $this->sektor_terdampaks = $sektor_terdampaks;
    }

    public function collection()
    {
        return $this->sektor_terdampaks->map(function ($item, $index) {
            return [
                $index + 1,
                $item->nama_lokasi ?? '',
                $item->indikator->nama ?? '',
                $item->wilayah->nama ?? '',                  // Kecamatan
                $item->alamat ?? '',                          // Alamat Detail
                $item->latitude ?? '',
                $item->longitude ?? '',
                $item->kondisi_awal ?? '',                    // Deskripsi Awal
                $item->kondisi ?? '',                         // Status (RR/RS/RB)
                $item->status ?? '',                          // Status Pemulihan
                $item->foto_sebelum == '' ? 'Tidak Ada' : 'Ada',                          // Status Pemulihan
                $item->foto_sesudah == '' ? 'Tidak Ada' : 'Ada',                          // Status Pemulihan
                '',                                           // Keterangan Tambahan
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Lokasi Terdampak ( Puskesmas .../Ruas Jalan, Jembatan, Rumah Ibadah, bangunan Huntap, dll pada 25 Indikator )',
            'Indikator',
            'Kecamatan',
            'Alamat Detail',
            'Latitude',
            'Longitude',
            'Deskripsi Awal',
            'Status ( RR/RS/RB )',
            'Status Pemulihan (Normal, Mendekati, Atensi)',
            'Foto Setelah Bencana',
            'Foto Saat Ini',
            'Keterangan Tambahan',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4.0,
            'B' => 35.33,
            'C' => 8.16,
            'D' => 10.33,
            'E' => 11.66,
            'F' => 7.5,
            'G' => 9.0,
            'H' => 13.16,
            'I' => 18.0,
            'J' => 17.66,
            'K' => 19.83,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF0070C0'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_TOP,
                    'wrapText'   => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set header row height to match template
                $sheet->getRowDimension(1)->setRowHeight(45);

                // Apply border to all data rows
                $lastRow = $sheet->getHighestRow();
                if ($lastRow > 1) {
                    $sheet->getStyle('A2:K' . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['argb' => 'FFD9D9D9'],
                            ],
                        ],
                        'alignment' => [
                            'vertical'  => Alignment::VERTICAL_TOP,
                            'wrapText'  => true,
                        ],
                    ]);
                }

                // Border on header row too
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => 'FF0070C0'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
