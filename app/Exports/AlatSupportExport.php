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

class AlatSupportExport implements FromCollection, WithEvents, WithHeadings, WithCustomStartCell, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $startTimeFormatted;
    protected $endTimeFormatted;

    // Konstruktor untuk menerima startTimeFormatted dan endTimeFormatted
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

                // Merge sesuai header
                $sheet->mergeCells('A1:A2'); // Tanggal Pelaporan
                $sheet->mergeCells('B1:B2'); // Shift
                $sheet->mergeCells('C1:C2'); // Area
                $sheet->mergeCells('D1:D2'); // Lokasi
                $sheet->mergeCells('E1:E2'); // Jenis Unit
                $sheet->mergeCells('F1:F2'); // Nomor Unit
                $sheet->mergeCells('G1:J1'); // Operator (NIK, NAMA, TANGGAL, SHIFT)
                $sheet->mergeCells('K1:L1'); // Foreman
                $sheet->mergeCells('M1:N1'); // Supervisor
                $sheet->mergeCells('O1:P1'); // Superintendent
                $sheet->mergeCells('Q1:T1'); // HM (Awal, Akhir, Total, Cash)
                $sheet->mergeCells('U1:U2'); // Keterangan
                $sheet->mergeCells('V1:V2'); // Status Draft

                // Style kolom
                $event->sheet->getStyle('A1:V2')->applyFromArray([
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical'   => 'center',
                    ],
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D8E4BC'],
                    ],
                ]);
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
                $dataRange = "A3:{$highestCol}{$highestRow}";
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DASHED,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                    // Autosize semua kolom
            foreach (range('A', $highestCol) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            },
        ];
    }



    public function styles(Worksheet $sheet)
    {
        return [
            // Gaya untuk header multilevel
            'A1:V2' => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D8E4BC', // Warna kuning
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            ['TANGGAL PELAPORAN', 'SHIFT', 'AREA', 'LOKASI', 'JENIS UNIT', 'NOMOR UNIT', 'OPERATOR', '', '', '', 'FOREMAN', '', 'SUPERVISOR', '' ,'SUPERINTENDENT', '' ,'HM', '', '', '', 'KETERANGAN', 'STATUS DRAFT'],
            ['', '', '', '', '', '', 'NIK', 'NAMA', 'TANGGAL', 'SHIFT', 'NIK', 'NAMA', 'NIK', 'NAMA', 'NIK', 'NAMA', 'AWAL', 'AKHIR', 'TOTAL', 'CASH', '', ''],
        ];
    }

    public function startCell(): string
    {
        return 'A1'; // Header akan dimulai dari baris ke-4
    }

    public function collection()
    {
        $support = DB::table('alat_support_t as al')
            ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
            ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
            ->leftJoin('REF_SHIFT as sh2', 'al.shift_operator_id', '=', 'sh2.id')
            ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
            ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
            // ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
            ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
            ->select(
                'dr.tanggal_dasar as tanggal_pelaporan',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'lok.keterangan as lokasi',
                'al.jenis_unit',
                'al.alat_unit as nomor_unit',
                'al.nik_operator',
                'al.nama_operator',
                'al.tanggal_operator',
                'sh2.keterangan as shift_operator',
                'dr.nik_foreman',
                'gl.PERSONALNAME as nama_foreman',
                'dr.nik_supervisor as nik_supervisor',
                'spv.PERSONALNAME as nama_supervisor',
                'dr.nik_superintendent as nik_superintendent',
                'spt.PERSONALNAME as nama_superintendent',
                'al.hm_awal',
                'al.hm_akhir',
                DB::raw('(al.hm_akhir - al.hm_awal) AS total_hm'),
                'al.hm_cash',
                'al.keterangan',
                'al.is_draft'
            )
            ->where('al.statusenabled', true)
            ->where('dr.statusenabled', true)
            ->whereBetween('dr.tanggal_dasar', [$this->startTimeFormatted, $this->endTimeFormatted]);
            if (Auth::user()->role !== 'ADMIN') {
                $support->where('dr.foreman_id', Auth::user()->id);
            }
        $support = $support->get();

        return $support;
    }
}
