<?php

namespace App\Http\Controllers;

use App\Exports\RosterKerjaExport;
use App\Imports\RosterKerjaImport;
use App\Models\RosterKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RosterKerjaController extends Controller
{
    //
    public function index(Request $request)
    {

        session(['requestRosterKerja' => $request->all()]);

        if (empty($request->tahun) || empty($request->bulan)){
            $bulan = now()->month; // Mendapatkan bulan sekarang
            $tahun = now()->year;

        }else{
            $bulan = $request->bulan;
            $tahun = $request->tahun;
        }


        $roster = DB::table('REF_ROSTER_KERJA as rs')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'rs.nik', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_TITLE as title', 'gl.ROLETYPE', '=', 'title.PTL_ID')
        ->select('rs.*', 'gl.PERSONALNAME as nama', 'title.PTL_DESC as jabatan')
        ->where('rs.statusenabled', true)
        ->whereRaw('CAST(rs.bulan AS INT) = ?', [$bulan])  // Pastikan bulan adalah numerik
        ->whereRaw('CAST(rs.tahun AS INT) = ?', [$tahun])
        ->get();

        // dd($roster);

        return view('roster-kerja.index', compact('roster', 'bulan', 'tahun'));
    }

    public function import(Request $request)
    {
        // Validasi file yang di-upload
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Maksimum 10MB
                'tahun' => 'required|numeric',
                'bulan' => 'required|numeric',
            ]);

            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');

            $file = $request->file('file');
            Excel::import(new RosterKerjaImport($tahun, $bulan), $file);

            return redirect()->back()->with('success', 'Berhasil import excel');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('import excel gagal..\n' . $th->getMessage()));
        }
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);

        // Bisa juga simpan ke session jika perlu
        session(['requestRosterKerja' => ['tahun' => $tahun, 'bulan' => $bulan]]);


        return Excel::download(new RosterKerjaExport($tahun, $bulan), 'Roster Kerja-'.$bulan.'-'.$tahun.'.xlsx');
    }

    public function templateExcel(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);

        // Bisa juga simpan ke session jika perlu
        session(['requestRosterKerja' => ['tahun' => $tahun, 'bulan' => $bulan]]);


        // dd($bulan);


        return Excel::download(new RosterKerjaExport($tahun, $bulan), 'Roster Kerja-'.$bulan.'-'.$tahun.'.xlsx');
    }
}
