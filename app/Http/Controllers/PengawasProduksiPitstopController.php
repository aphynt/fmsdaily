<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengawasProduksiPitstopController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeLaporanKerjaOBCoal' => $request->all()]);

        if (empty($request->rangeStart) || empty($request->rangeEnd)) {
            $time = new DateTime();
            $start = new DateTime($time->format('Y-m-d'));
            $end   = new DateTime($time->format('Y-m-d'));
        } else {
            $start = new DateTime($request->rangeStart);
            $end   = new DateTime($request->rangeEnd);
        }

        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted   = $end->format('Y-m-d');

        // ================= OB =================
        $ob = DB::table('daily_report_t as dr')
            ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
            ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
            ->leftJoin('users as us3', 'dr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'dr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'dr.nik_superintendent', '=', 'us5.nik')
            ->selectRaw("
                dr.id,
                dr.uuid,
                dr.tanggal_dasar as tanggal,
                sh.keterangan as shift,
                ar.keterangan as area,
                lok.keterangan as lokasi,
                us.name as pic,
                us.nik as nik_pic,
                dr.nik_foreman,
                us3.name as nama_foreman,
                dr.nik_supervisor,
                us4.name as nama_supervisor,
                dr.nik_superintendent,
                us5.name as nama_superintendent,
                dr.is_draft,
                dr.verified_supervisor,
                dr.verified_superintendent,
                dr.created_at,
                dr.updated_at,
                'OB' as unit_kerja
            ")
            ->whereBetween('dr.tanggal_dasar', [$startTimeFormatted, $endTimeFormatted])
            ->where('dr.statusenabled', true);

        $ob = $ob->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('dr.nik_foreman', Auth::user()->nik)
                    ->orWhere('dr.nik_supervisor', Auth::user()->nik)
                    ->orWhere('dr.nik_superintendent', Auth::user()->nik);
            }
        });

        // ================= COAL =================
        $coal = DB::table('PITSTOP_REPORT as pr')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->selectRaw("
                pr.id,
                pr.uuid,
                pr.date as tanggal,
                sh.keterangan as shift,
                ar.keterangan as area,
                NULL as lokasi,
                us.name as pic,
                us.nik as nik_pic,
                pr.nik_foreman,
                us3.name as nama_foreman,
                pr.nik_supervisor,
                us4.name as nama_supervisor,
                pr.nik_superintendent,
                us5.name as nama_superintendent,
                pr.is_draft,
                pr.verified_supervisor,
                pr.verified_superintendent,
                pr.created_at,
                pr.updated_at,
                'Pitstop' as unit_kerja
            ")
            ->whereBetween('pr.date', [$startTimeFormatted, $endTimeFormatted])
            ->where('pr.statusenabled', true);

        $coal = $coal->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('pr.nik_foreman', Auth::user()->nik)
                    ->orWhere('pr.nik_supervisor', Auth::user()->nik);
                    // ->orWhere('pr.nik_superintendent', Auth::user()->nik);
            }
        });

        // ================ GABUNG OB + COAL =================
        $laporan = $ob->unionAll($coal);

        // Kalau mau di-order:
        $data = DB::query()
            ->fromSub($laporan, 'lap')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pengawas-produksi-pitstop.index', compact('data'));
    }

}
