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
use PhpOffice\PhpSpreadsheet\Style\Border;

class JobPendingExport implements FromCollection, WithEvents, WithHeadings, WithCustomStartCell, WithStyles
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

                // lebar kolom
                $sheet->getColumnDimension('A')->setWidth(21);
                $sheet->getColumnDimension('D')->setWidth(21);
                $sheet->getColumnDimension('E')->setWidth(40);
                $sheet->getColumnDimension('F')->setWidth(17);
                $sheet->getColumnDimension('G')->setWidth(50);
                $sheet->getColumnDimension('H')->setWidth(30);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(50);
                $sheet->getColumnDimension('L')->setWidth(21);
                $sheet->getColumnDimension('M')->setWidth(20);

                // aktifkan wrap text kolom J
                $highestRow = $sheet->getHighestRow(); // baris terakhir
                $sheet->getStyle("J3:J{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);
            },
        ];
    }


    public function styles(Worksheet $sheet)
    {
        // Cari baris terakhir (jumlah data)
        $lastRow = $sheet->getHighestRow();

        return [
            // Gaya untuk header multilevel
            'A1:M2' => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D8E4BC',
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Border putus-putus untuk data (mulai baris 3 sampai akhir)
            "A1:M{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_DOTTED, // putus-putus
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            [
                'TANGGAL PENDING',
                'SHIFT',
                'PEMBUAT', '',
                'LOKASI',
                'SECTION',
                'AKTIVITAS',
                'UNIT',
                'ELEVASI',
                'ISSUE',
                'PENERIMA', '',
                'STATUS VERIFIKASI',
            ],
            [
                '',
                '',
                'NIK', 'NAMA',
                '',
                '',
                '',
                '',
                '',
                '',
                'NIK', 'NAMA',
                '',
            ],
        ];
    }


    public function startCell(): string
    {
        return 'A1'; // Header akan dimulai dari baris ke-4
    }

    public function collection()
    {

        $dataQuery = DB::table('JOB_PENDING as jp')
        ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
            ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
            ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
            ->leftJoin('focus.dbo.PRS_PERSONAL as db', 'jp.dibuat', '=', 'db.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as dt', 'jp.diterima', '=', 'dt.NRP')
            ->select(
                'jp.date',
                'sh.keterangan as shift',
                'us.nik as nik_pic',
                'us.name as pic',
                'jp.lokasi',
                'sec.keterangan as section',
                'jd.aktivitas',
                'jd.unit',
                'jd.elevasi',
                'jp.issue',
                'jp.diterima as nik_diterima',
                'dt.PERSONALNAME as nama_diterima',
                DB::raw("CASE WHEN jp.done = 1 THEN 'Telah diverifikasi' ELSE 'Belum diverifikasi' END as status_job")
            )
            ->where('jp.statusenabled', true)
            ->whereBetween('jp.date', [$this->startTimeFormatted, $this->endTimeFormatted]);

        $dataQuery = $dataQuery->get();

        return $dataQuery;

    }
}
