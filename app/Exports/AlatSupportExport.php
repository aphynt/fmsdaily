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
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(21);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(11);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(13);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(20);
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
