<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengawasPitstopExport implements FromCollection, WithEvents, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $startTimeFormatted;
    protected $endTimeFormatted;
    private $rowNumber = 0;

    protected $metaRows = [];

    public function __construct($startTimeFormatted, $endTimeFormatted)
    {
        $this->startTimeFormatted = $startTimeFormatted;
        $this->endTimeFormatted = $endTimeFormatted;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Atur lebar kolom
                foreach (range('A','P') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Merge cell untuk header sesuai <thead>
                $sheet->mergeCells('A1:A2'); // No
                $sheet->mergeCells('B1:B2'); // Tanggal
                $sheet->mergeCells('C1:C2'); // Shift
                $sheet->mergeCells('D1:D2'); // Lokasi
                $sheet->mergeCells('E1:F1'); // PIC
                $sheet->mergeCells('G1:G2'); // Jenis Unit
                $sheet->mergeCells('H1:H2'); // Type Unit
                $sheet->mergeCells('I1:I2'); // No Unit
                $sheet->mergeCells('J1:J2'); // Operator Settingan
                $sheet->mergeCells('K1:N1'); // Status
                $sheet->mergeCells('O1:O2'); // Operator (Ready)
                $sheet->mergeCells('P1:P2'); // Ket.

                // Style header
                $sheet->getStyle('A1:P2')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D8E4BC'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Terapkan pewarnaan/baris khusus
                $startRow = 3; // data mulai baris ke-3
                foreach ($this->metaRows as $index => $meta) {
                    $rowIndex = $startRow + $index;

                    if (!empty($meta['isDifferentOpr'])) {
                        $sheet->getStyle("J{$rowIndex}")->getFont()->getColor()->setRGB('0000FF');
                        $sheet->getStyle("O{$rowIndex}")->getFont()->getColor()->setRGB('0000FF');
                    }

                    if (!empty($meta['isOutsideShift'])) {
                        $sheet->getStyle("K{$rowIndex}")->getFont()->setBold(true);
                        $sheet->getStyle("P{$rowIndex}")->getFont()->setBold(true);
                    }

                    if (!empty($meta['totalMinutes']) && $meta['totalMinutes'] > 30) {
                        $sheet->getStyle("N{$rowIndex}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']],
                        ]);
                    }
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // GLOBAL: border + rata tengah
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // OVERRIDE: kolom tertentu jadi rata kiri
        // F = Nama PIC
        $sheet->getStyle("F3:F{$highestRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // J = Operator (Settingan)
        $sheet->getStyle("J3:J{$highestRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // O = Operator (Ready)
        $sheet->getStyle("O3:O{$highestRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // P = Ket.
        $sheet->getStyle("P3:P{$highestRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
            ->setWrapText(true);   // biar kalau panjang turun ke bawah
    }

    public function headings(): array
    {
        return [
            [
                'No', 'Tanggal', 'Shift', 'Lokasi',
                'PIC', '', // merge E1:F1
                'Jenis Unit', 'Type Unit', 'No. Unit', 'Operator (Settingan)',
                'Status', '', '', '', // merge K1:N1
                'Operator (Ready)', 'Ket.'
            ],
            [
                '', '', '', '',
                'NIK', 'Nama',
                '', '', '', '',
                'Unit Breakdown', 'Unit Ready', 'Operator Ready', 'Durasi',
                '', ''
            ]
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->select(
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.nik as nik_pic',
                'us.name as pic',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->whereBetween('pr.date', [$this->startTimeFormatted, $this->endTimeFormatted]);

        // if (Auth::user()->role !== 'ADMIN') {
        //     $dailyDesc->where('pr.nik_foreman', Auth::user()->id);
        // }

        $rows = $dailyDesc->get();

        $this->metaRows = [];
        $rowNumber = 0;

        $exportRows = $rows->map(function ($sp) use (&$rowNumber) {
            $rowNumber++;

            // 1. cek beda operator
            $isDifferentOpr = ($sp->opr_settingan !== $sp->opr_ready);

            // 2. cek di luar shift
            $isOutsideShift = false;
            $time_breakdown = '';
            if ($sp->status_unit_breakdown) {
                $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                $isOutsideShift = ($sp->shift !== $shiftFromTime);
                $time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
            }

            // 3. hitung durasi efektif
            $totalMinutes = 0;
            $durasi_eff = '00:00:00';

            if ($sp->status_unit_ready && $sp->status_opr_ready) {
                $start = strtotime($sp->status_unit_ready);
                $end   = strtotime($sp->status_opr_ready);

                // 👇 PERBAIKAN PENTING: kalau selesai lebih kecil dari mulai → berarti nyebrang hari
                if ($end < $start) {
                    $end = strtotime('+1 day', $end);
                }

                $totalMinutes = ($end - $start) / 60;

                // kurangi jam istirahat 12:00-13:00 di tanggal start
                $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                $overlapStart = max($start, $breakStart);
                $overlapEnd   = min($end, $breakEnd);
                $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart) / 60 : 0;

                $totalMinutes -= $breakMinutes;

                // antisipasi kalau masih minus karena kasus aneh
                if ($totalMinutes < 0) {
                    // misal dihitungnya jadi -1351 → anggap crossing 24 jam
                    $totalMinutes = 1440 + $totalMinutes;
                }

                $durasi_eff = gmdate('H:i:s', $totalMinutes * 60);
            }

            // 4. format jam
            $status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
            $status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

            // 5. simpan meta utk pewarnaan di AfterSheet
            $this->metaRows[] = [
                'isDifferentOpr' => $isDifferentOpr,
                'isOutsideShift' => $isOutsideShift,
                'totalMinutes'   => $totalMinutes,
            ];

            // 6. keterangan null → kosong
            $keterangan = $sp->keterangan ?? '';

            return [
                $rowNumber,
                $sp->tanggal,
                $sp->shift,
                $sp->area,
                $sp->nik_pic,
                $sp->pic,
                $sp->jenis_unit,
                $sp->type_unit,
                $sp->no_unit,
                $sp->opr_settingan . '-' . $sp->nama_opr_settingan,
                $time_breakdown,
                $status_unit_ready_fmt,
                $status_opr_ready_fmt,
                $durasi_eff,
                $sp->opr_ready . '-' . $sp->nama_opr_ready,
                $keterangan,
            ];
        });

        return collect($exportRows);
    }

}
