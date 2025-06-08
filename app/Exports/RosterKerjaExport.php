<?php

namespace App\Exports;

use App\Models\RosterKerja;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class RosterKerjaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $tahun;
    protected $bulan;

    // Konstruktor untuk menerima tahun dan bulan
    public function __construct($tahun, $bulan)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        $roster = DB::table('REF_ROSTER_KERJA as rs')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'rs.nik', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_TITLE as title', 'gl.ROLETYPE', '=', 'title.PTL_ID')
        ->select(
            'rs.id',
            'title.PTL_DESC as jabatan',
            'rs.unit_kerja',
            'rs.nik',
            'gl.PERSONALNAME as nama',
            'rs.1',
            'rs.2',
            'rs.3',
            'rs.4',
            'rs.5',
            'rs.6',
            'rs.7',
            'rs.8',
            'rs.9',
            'rs.10',
            'rs.11',
            'rs.12',
            'rs.13',
            'rs.14',
            'rs.15',
            'rs.16',
            'rs.17',
            'rs.18',
            'rs.19',
            'rs.20',
            'rs.21',
            'rs.22',
            'rs.23',
            'rs.24',
            'rs.25',
            'rs.26',
            'rs.27',
            'rs.28',
            'rs.29',
            'rs.30',
            'rs.31',
            )
        ->where('rs.statusenabled', true)
        ->whereRaw('CAST(rs.bulan AS INT) = ?', [$this->bulan])
        ->whereRaw('CAST(rs.tahun AS INT) = ?', [$this->tahun])
        ->get();

        // dd($roster);

        return $roster;
    }
}
