<?php

namespace App\Http\Controllers;

use App\Models\BBCatatanPengawas;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BBCatatanPengawasController extends Controller
{
    //
    public function index(Request $request)
    {
        if (empty($request->rangeStart) || empty($request->rangeEnd)){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$request->rangeStart");
            $end = new DateTime("$request->rangeEnd");

        }else{
            $start = new DateTime("$request->rangeStart");
            $end = new DateTime("$request->rangeEnd");
        }

        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');

        $catatan = DB::table('BB_CATATAN_PENGAWAS as cp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'cp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'cp.daily_report_id as id',
            'cp.uuid',
            'sh.keterangan as shift',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            DB::raw("SUBSTRING(CONVERT(varchar, cp.jam_start, 108), 1, 5) as jam_start"),
            DB::raw("SUBSTRING(CONVERT(varchar, cp.jam_stop, 108), 1, 5) as jam_stop"),
            'cp.keterangan',
            'cp.is_draft'
        )
        ->where('cp.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->whereBetween(DB::raw("CONVERT(varchar, dr.tanggal_dasar, 23)"), [$startTimeFormatted, $endTimeFormatted]);

        if (Auth::user()->role !== 'ADMIN') {
            $catatan->where('dr.foreman_id', Auth::user()->id);
        }

        $catatan = $catatan->get();
        // dd($catatan);

        return view('batubara.catatan-pengawas.index', compact('catatan'));
    }

    public function destroy($id)
    {
        try {
            $note = BBCatatanPengawas::findOrFail($id);
            $note->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus catatan.',
            ], 500);
        }
    }
}
