<?php

namespace App\Http\Controllers;

use App\Models\CatatanPengawas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Auth;

class CatatanPengawasController extends Controller
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

        $note = DB::table('catatan_pengawas_t as cp')
        ->leftJoin('daily_report_t as dr', 'cp.daily_report_id', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        // ->leftJoin('users as us', 'dr.foreman_id', 'us.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'cp.daily_report_id as id',
            'dr.tanggal_dasar as tanggal_pelaporan',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'cp.jam_start',
            'cp.jam_stop',
            'cp.keterangan'
        )
        ->where('cp.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->whereBetween('tanggal_dasar', [$startTimeFormatted, $endTimeFormatted]);
        if (Auth::user()->role !== 'ADMIN') {
            $note->where('dr.foreman_id', Auth::user()->id);
        }

        $note = $note->get();

        return view('catatan-pengawas.index', compact('note'));
    }

    public function destroy($id)
    {
        try {
            $note = CatatanPengawas::findOrFail($id);
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
