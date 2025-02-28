<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\BBCatatanPengawas;
use App\Models\BBDailyReport;
use App\Models\BBLoadingPoint;
use App\Models\BBUnitSupport;
use App\Models\JenisSubcont;
use App\Models\Log;
use App\Models\NoUnitSubcont;
use App\Models\PengawasSubcont;
use App\Models\Personal;
use App\Models\Shift;
use App\Models\Subcont;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class FormPengawasBatuBaraController extends Controller
{
    //
    public function index()
    {
        $today = now()->toDateString();
        $daily = BBDailyReport::where('foreman_id', Auth::user()->id)
            ->where(function ($query) use ($today) {
                $query->where('is_draft', true)
                    ->orWhere(function ($q) use ($today) {
                        $q->where('is_draft', false)
                          ->whereDate('created_at', '!=', $today);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if ($daily == null) {
            $daily = null;
        } else {
            if ($daily['is_draft'] == false ) {
                $daily = null;
            }
        }

        if ($daily) {
            $daily['nik_superintendent'] = $daily['nik_superintendent'] . '|' . $daily['nama_superintendent'];
            $daily['nik_supervisor'] = $daily['nik_supervisor'] . '|' . $daily['nama_supervisor'];

            if (!empty($daily['tanggal_dasar'])) {
                $tanggalDasar = new DateTimeImmutable($daily['tanggal_dasar']);
                $daily['tanggal_dasar'] = $tanggalDasar ? $tanggalDasar->format('m/d/Y') : $daily['tanggal_dasar'];
            }
        }

        $loadingPoints = [];
        $unitSupports = [];
        $supervisorNotes = [];

        if ($daily) {
            $loadingPoints = BBLoadingPoint::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();


            $unitSupports = BBUnitSupport::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();

            $supervisorNotes = BBCatatanPengawas::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();
        }

            $supervisor = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
        )->where('ROLETYPE', 3)->get();

        $superintendent = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY',
            DB::raw("CASE WHEN ROLETYPE = 3 THEN 'SUPERVISOR' WHEN ROLETYPE = 4 THEN 'SUPERINTENDENT' ELSE 'UNKNOWN' END as JABATAN ")
        )->whereIn('ROLETYPE', [3, 4])->get();

        $shift = Shift::where('statusenabled', true)->get();
        $area = Area::where('statusenabled', true)->get();
        $jenisSupport = JenisSubcont::where('statusenabled', true)->get();
        $subcontSupport = Subcont::where('statusenabled', true)->get();
        $noUnitSupport = NoUnitSubcont::where('statusenabled', true)->get();
        $fleetEX = NoUnitSubcont::where('statusenabled', true)->where('keterangan','LIKE', 'EX%')->get();
        $pengawasSubcont = PengawasSubcont::where('statusenabled', true)->get();

        $data = [
            'supervisor' => $supervisor,
            'superintendent' => $superintendent,
            'shift' => $shift,
            'area' => $area,
            'jenisSupport' => $jenisSupport,
            'subcontSupport' => $subcontSupport,
            'fleetEX' => $fleetEX,
            'noUnitSupport' => $noUnitSupport,
            'pengawasSubcont' => $pengawasSubcont,

        ];

        return view('form-pengawas-batubara.index', compact('daily', 'data', 'loadingPoints', 'unitSupports', 'supervisorNotes'));
    }

    public function show(Request $request)
    {
        session(['requestTimeLaporanKerjaBatuBara' => $request->all()]);

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


        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.is_draft',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
            'dr.created_at',

        )
        ->whereBetween('dr.tanggal_dasar', [$startTimeFormatted, $endTimeFormatted])
        ->where('dr.statusenabled', true);


        $daily = $daily->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('dr.nik_foreman', Auth::user()->nik)
                  ->orWhere('dr.nik_supervisor', Auth::user()->nik)
                  ->orWhere('dr.nik_superintendent', Auth::user()->nik);
            }
        });

        // if (Auth::user()->role == 'FOREMAN') {
        //     $daily->where('dr.nik_foreman', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERVISOR') {
        //     $daily->where('dr.nik_supervisor', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERINTENDENT') {
        //     $daily->where('dr.nik_superintendent', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'ADMIN') {
        //     $daily->orWhere('pic', Auth::user()->id);
        // }

        $daily = $daily->get();

        // dd($daily);

        return view('form-pengawas-batubara.daftar.index', compact('daily'));
    }

    public function preview($uuid)
    {

        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.is_draft',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
            'dr.created_at',

        )
        ->where('dr.statusenabled', true)
        ->where('dr.uuid', $uuid)->first();

        if($daily == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $daily->verified_foreman = $daily->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;
        }

        $loading = DB::table('BB_LOADING_POINT as lp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'lp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_SUBCONT as sc', 'lp.subcont', '=', 'sc.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit', '=', 'ar.id')
        // ->leftJoin('REF_SUBCONT_PENGAWAS as pg', 'lp.pengawas', '=', 'pg.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'lp.fleet_ex', '=', 'su.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'lp.daily_report_id as id',
            'lp.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'sc.keterangan as subcont',
            // 'su.keterangan as nomor_unit',
            'ar.keterangan as pit',
            'lp.pengawas',
            'lp.fleet_ex',
            // 'pg.keterangan as pengawas',
            // 'su.keterangan as fleet_ex',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lp.jumlah_dt',
            'lp.seam_bb',
            'lp.jarak',
            'lp.keterangan',
            'lp.is_draft'
        )
        ->where('lp.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('lp.daily_report_uuid', $uuid)->get();

        $support = DB::table('BB_UNIT_SUPPORT as us')
        ->leftJoin('BB_DAILY_REPORT as dr', 'us.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SUBCONT as sc', 'us.subcont', '=', 'sc.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'us.nomor_unit', '=', 'su.id')
        ->leftJoin('REF_SUBCONT_JENIS_SUPPORT as js', 'us.jenis', '=', 'js.id')
        ->leftJoin('REF_AREA as ar', 'us.area', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'us.daily_report_id as id',
            'us.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'js.keterangan as jenis',
            'sc.keterangan as subcont',
            'us.nomor_unit',
            'js.keterangan as jenis',
            'ar.keterangan as area',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'us.keterangan',
            'us.is_draft'
        )
        ->where('us.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('us.daily_report_uuid', $uuid)->get();

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
        ->where('cp.daily_report_uuid', $uuid)->get();

        $data = [
            'daily' => $daily,
            'loading' => $loading,
            'support' => $support,
            'catatan' => $catatan,
        ];
        // dd($data);

        return view('form-pengawas-batubara.preview', compact('data'));
    }

    public function download($uuid)
    {

        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.is_draft',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
            'dr.created_at',

        )
        ->where('dr.statusenabled', true)
        ->where('dr.uuid', $uuid)->first();

        if($daily == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $daily->verified_foreman = $daily->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;
        }

        $loading = DB::table('BB_LOADING_POINT as lp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'lp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_SUBCONT as sc', 'lp.subcont', '=', 'sc.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit', '=', 'ar.id')
        // ->leftJoin('REF_SUBCONT_PENGAWAS as pg', 'lp.pengawas', '=', 'pg.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'lp.fleet_ex', '=', 'su.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'lp.daily_report_id as id',
            'lp.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'sc.keterangan as subcont',
            // 'su.keterangan as nomor_unit',
            'ar.keterangan as pit',
            'lp.pengawas',
            'lp.fleet_ex',
            // 'pg.keterangan as pengawas',
            // 'su.keterangan as fleet_ex',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lp.jumlah_dt',
            'lp.seam_bb',
            'lp.jarak',
            'lp.keterangan',
            'lp.is_draft'
        )
        ->where('lp.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('lp.daily_report_uuid', $uuid)->get();

        $support = DB::table('BB_UNIT_SUPPORT as us')
        ->leftJoin('BB_DAILY_REPORT as dr', 'us.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SUBCONT as sc', 'us.subcont', '=', 'sc.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'us.nomor_unit', '=', 'su.id')
        ->leftJoin('REF_SUBCONT_JENIS_SUPPORT as js', 'us.jenis', '=', 'js.id')
        ->leftJoin('REF_AREA as ar', 'us.area', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'us.daily_report_id as id',
            'us.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'js.keterangan as jenis',
            'sc.keterangan as subcont',
            'us.nomor_unit',
            'js.keterangan as jenis',
            'ar.keterangan as area',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'us.keterangan',
            'us.is_draft'
        )
        ->where('us.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('us.daily_report_uuid', $uuid)->get();

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
        ->where('cp.daily_report_uuid', $uuid)->get();

        $data = [
            'daily' => $daily,
            'loading' => $loading,
            'support' => $support,
            'catatan' => $catatan,
        ];

        return view('form-pengawas-batubara.download', compact('data'));
    }

    public function pdf($uuid)
    {

        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.is_draft',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
            'dr.created_at',

        )
        ->where('dr.statusenabled', true)
        ->where('dr.uuid', $uuid)->first();

        if($daily == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $daily->verified_foreman = $daily->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman)) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor)) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent)) : null;

        }

        $loading = DB::table('BB_LOADING_POINT as lp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'lp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_SUBCONT as sc', 'lp.subcont', '=', 'sc.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit', '=', 'ar.id')
        // ->leftJoin('REF_SUBCONT_PENGAWAS as pg', 'lp.pengawas', '=', 'pg.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'lp.fleet_ex', '=', 'su.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'lp.daily_report_id as id',
            'lp.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'sc.keterangan as subcont',
            // 'su.keterangan as nomor_unit',
            'ar.keterangan as pit',
            'lp.pengawas',
            'lp.fleet_ex',
            // 'pg.keterangan as pengawas',
            // 'su.keterangan as fleet_ex',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lp.jumlah_dt',
            'lp.seam_bb',
            'lp.jarak',
            'lp.keterangan',
            'lp.is_draft'
        )
        ->where('lp.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('lp.daily_report_uuid', $uuid)->get();

        $support = DB::table('BB_UNIT_SUPPORT as us')
        ->leftJoin('BB_DAILY_REPORT as dr', 'us.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SUBCONT as sc', 'us.subcont', '=', 'sc.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'us.nomor_unit', '=', 'su.id')
        ->leftJoin('REF_SUBCONT_JENIS_SUPPORT as js', 'us.jenis', '=', 'js.id')
        ->leftJoin('REF_AREA as ar', 'us.area', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'us.daily_report_id as id',
            'us.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'js.keterangan as jenis',
            'sc.keterangan as subcont',
            'us.nomor_unit',
            'js.keterangan as jenis',
            'ar.keterangan as area',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'us.keterangan',
            'us.is_draft'
        )
        ->where('us.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->where('us.daily_report_uuid', $uuid)->get();

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
        ->where('cp.daily_report_uuid', $uuid)->get();

        $data = [
            'daily' => $daily,
            'loading' => $loading,
            'support' => $support,
            'catatan' => $catatan,
        ];

        $pdf = PDF::loadView('form-pengawas-batubara.pdf', compact(['data']));
        return $pdf->stream('Laporan Kerja Batu Bara-'. $data['daily']->tanggal .'-'. $data['daily']->shift .'-'. $data['daily']->pic .'.pdf');
    }

    public function bundlepdf()
    {

        if (empty(session('requestTimeLaporanKerjaBatuBara')['rangeStart']) || empty(session('requestTimeLaporanKerjaBatuBara')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeLaporanKerjaBatuBara')['rangeStart']);
            $end = new DateTime(session('requestTimeLaporanKerjaBatuBara')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');

        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.is_draft',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
            'dr.created_at',

        )
        ->whereBetween(DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23)'), [$startTimeFormatted, $endTimeFormatted])
        ->where('dr.statusenabled', true)
        ->get();


        // Convert to collection
        $daily = collect($daily); // Ensure the data is a collection

        // Check if no data found for daily report
        if ($daily->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        $loading = DB::table('BB_LOADING_POINT as lp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'lp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_SUBCONT as sc', 'lp.subcont', '=', 'sc.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit', '=', 'ar.id')
        // ->leftJoin('REF_SUBCONT_PENGAWAS as pg', 'lp.pengawas', '=', 'pg.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'lp.fleet_ex', '=', 'su.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'lp.daily_report_id as id',
            'lp.daily_report_uuid',
            'lp.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'sc.keterangan as subcont',
            // 'su.keterangan as nomor_unit',
            'ar.keterangan as pit',
            'lp.pengawas',
            'lp.fleet_ex',
            // 'pg.keterangan as pengawas',
            // 'su.keterangan as fleet_ex',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lp.jumlah_dt',
            'lp.seam_bb',
            'lp.jarak',
            'lp.keterangan',
            'lp.is_draft'
        )
        ->where('lp.statusenabled', true)
            ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $loading = collect($loading);

        $support = DB::table('BB_UNIT_SUPPORT as us')
        ->leftJoin('BB_DAILY_REPORT as dr', 'us.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SUBCONT as sc', 'us.subcont', '=', 'sc.id')
        // ->leftJoin('REF_SUBCONT_UNIT as su', 'us.nomor_unit', '=', 'su.id')
        ->leftJoin('REF_SUBCONT_JENIS_SUPPORT as js', 'us.jenis', '=', 'js.id')
        ->leftJoin('REF_AREA as ar', 'us.area', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'us.daily_report_id as id',
            'us.daily_report_uuid',
            'us.uuid',
            DB::raw('CONVERT(varchar, dr.tanggal_dasar, 23) as tanggal_pelaporan'),
            'sh.keterangan as shift',
            'js.keterangan as jenis',
            'sc.keterangan as subcont',
            'us.nomor_unit',
            'js.keterangan as jenis',
            'ar.keterangan as area',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'us.keterangan',
            'us.is_draft'
        )
        ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $support = collect($support);

        $catatan = DB::table('BB_CATATAN_PENGAWAS as cp')
        ->leftJoin('BB_DAILY_REPORT as dr', 'cp.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'cp.daily_report_id',
            'cp.daily_report_uuid',
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
        ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $catatan = collect($catatan);

        // Combine all data into one collection
        $combinedData = $daily->map(function ($dailyItem) use ($loading, $support, $catatan) {
            // Relate front loading data to daily report using 'uuid'
            $loadingPoint = $loading->filter(function ($item) use ($dailyItem) {
                return $item->daily_report_uuid == $dailyItem->uuid; // Assuming 'uuid' is the relation key
            });

            // Relate support equipment data to daily report using 'uuid'
            $supportData = $support->filter(function ($item) use ($dailyItem) {
                return $item->daily_report_uuid == $dailyItem->uuid; // Assuming 'uuid' is the relation key
            });
            // dd($supportData);

            // Relate catatan data to daily report using 'uuid'
            $catatanData = $catatan->filter(function ($item) use ($dailyItem) {
                return $item->daily_report_uuid == $dailyItem->uuid; // Assuming 'uuid' is the relation key
            });

            $dataDummy = [
                'dailyReport' => $dailyItem,
                'loadingPoint' => $loadingPoint,
                'unitSupport' => $supportData,
                'catatanPengawas' => $catatanData,
            ];

            return $dataDummy;
        });


        // Process the combined data if needed, for example, encoding QR codes for verification
        $combinedData = $combinedData->map(function ($data) {
            $data['dailyReport']->verified_foreman = $data['dailyReport']->verified_foreman ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $data['dailyReport']->nama_foreman)) : null;

            $data['dailyReport']->verified_supervisor = $data['dailyReport']->verified_supervisor ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $data['dailyReport']->nama_supervisor)) : null;

            $data['dailyReport']->verified_superintendent = $data['dailyReport']->verified_superintendent ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $data['dailyReport']->nama_superintendent)) : null;

            return $data;
        });



        $pdf = PDF::loadView('form-pengawas-batubara.bundlepdf', compact('combinedData'));
        return $pdf->stream('Laporan Kerja Batu Bara.pdf');

        // return view('form-pengawas-old.bundlepdf', compact('combinedData'));
    }

    public function post(Request $request)
    {

        $shift = Shift::where('id', $request->shift_dasar)->first();

        Log::create([
            'tanggal_loging' => now(),
            'jenis_loging' => 'Laporan Kerja',
            'nama_user' => Auth::user()->id,
            'nik' => Auth::user()->nik,
            'keterangan' => 'Tambah laporan kerja batu bara dengan nama: '. Auth::user()->name . ', NIK: '. Auth::user()->nik . ', Role: '. Auth::user()->role .
            ', shift: '. $shift->keterangan
        ]);
        // dd($request->all());
        try {
            return DB::transaction(function () use ($request) {

                    BBDailyReport::where('uuid', $request->uuid)->update(['is_draft' => false]);
                    BBLoadingPoint::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);
                    BBUnitSupport::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);
                    BBCatatanPengawas::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);
            return redirect()->route('form-pengawas-new.show')->with('success', 'Laporan berhasil dibuat');
            });
        } catch (\Throwable $th) {
           return redirect()->route('form-pengawas-new.index')->with('info', 'Laporan gagal dibuat.. \n' . $th->getMessage());
        }
    }

    public function verifiedAll($uuid)
    {
        $klkh =  BBDailyReport::where('uuid', $uuid)->first();

        try {
            BBDailyReport::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan berhasil gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman($uuid)
    {
        $klkh =  BBDailyReport::where('uuid', $uuid)->first();

        try {
            BBDailyReport::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor($uuid)
    {
        $klkh =  BBDailyReport::where('uuid', $uuid)->first();

        try {
            BBDailyReport::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent($uuid)
    {
        $klkh =  BBDailyReport::where('uuid', $uuid)->first();

        try {
            BBDailyReport::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function saveAsDraft(Request $request)
    {

        try {
            return DB::transaction(function () use ($request) {
                $typeDraft = true;
                if($request->actionType == 'finish'){
                    $typeDraft = false;
                }
                $uuid = $request->uuid;

            // Jika UUID tidak kosong, cek apakah draft sudah ada
            $dailyReport = !empty($uuid)
                ? BBDailyReport::where('uuid', $uuid)->first()
                : null;

                $uuid = $dailyReport ? $dailyReport->uuid : Uuid::uuid4()->toString();


                $nikSupervisor = null;
                $namaSupervisor = null;

                if (!empty($request->nik_supervisor)) {
                    [$nikSupervisor, $namaSupervisor] = explode('|', $request->nik_supervisor) + [null, null];
                } else {
                    $nikSupervisor = $dailyReport->nik_supervisor ?? null;
                    $namaSupervisor = $dailyReport->nama_supervisor ?? null;
                }

                if (!empty($request->nik_superintendent)) {
                    [$nikSuperintendent, $namaSuperintendent] = explode('|', $request->nik_superintendent) + [null, null];
                } else {
                    $nikSuperintendent = $dailyReport->nik_superintendent ?? null;
                    $namaSuperintendent = $dailyReport->nama_superintendent ?? null;
                }



            $data = [
                'uuid' => $uuid ?? Uuid::uuid4()->toString(),
                'foreman_id' => Auth::id(),
                'statusenabled' => true,
                'tanggal_dasar' => $request->filled('tanggal_dasar')
                    ? now()->parse($request->tanggal_dasar)->format('Y-m-d')
                    : null,
                'shift_dasar_id' => $request->shift_dasar,
                'nik_superintendent' => $nikSuperintendent,
                'nama_superintendent' => $namaSuperintendent,
                'nik_supervisor' => $nikSupervisor,
                'nama_supervisor' => $namaSupervisor,
                'is_draft' => $typeDraft, // Default sebagai draft
            ];

            // Tambahkan data berdasarkan role pengguna
            if (Auth::user()->role === 'SUPERVISOR') {
                $data['nik_supervisor'] = Auth::user()->nik;
                $data['nama_supervisor'] = Auth::user()->name;
                $data['verified_supervisor'] = Auth::user()->nik;
            }

            if (Auth::user()->role === 'FOREMAN') {
                $data['nik_foreman'] = Auth::user()->nik;
                $data['nama_foreman'] = Auth::user()->name;
                $data['verified_foreman'] = Auth::user()->nik;
            }

            // Gunakan updateOrCreate untuk menghindari duplikasi
            $dailyReport = BBDailyReport::updateOrCreate(
                ['uuid' => $uuid], // Kondisi pencarian
                $data
            );



            //Insert & Update Loading Point
            if (!empty($request->loading_point)) {
                $loadingPoints = json_decode($request->loading_point, true);
                foreach ($loadingPoints as $value) {
                    BBLoadingPoint::updateOrCreate(
                        [
                            'uuid' => $value['uuid'],
                        ],
                        [
                            'daily_report_uuid' => $dailyReport->uuid,
                            'daily_report_id' => $dailyReport->id,
                            'statusenabled' => true,
                            'subcont' => $value['subcont'] ?? null,
                            'pit' => $value['pit'] ?? null,
                            'pengawas' => $value['pengawas'] ?? null,
                            'fleet_ex' => $value['fleet_ex'] ?? null,
                            'jumlah_dt' => $value['jumlah_dt'] ?? null,
                            'seam_bb' => $value['seam_bb'] ?? null,
                            'jarak' => $value['jarak'] ?? null,
                            'keterangan' => $value['keterangan'] ?? null,
                            'is_draft' => $typeDraft,
                        ]
                    );
                }
            }

            //Insert & Update Unit Support
            if (!empty($request->unit_support)) {
                $unitSupports = json_decode($request->unit_support, true);
                foreach ($unitSupports as $value) {
                    BBUnitSupport::updateOrCreate(
                        [
                            'uuid' => $value['uuid'],
                        ],
                        [
                            'daily_report_uuid' => $dailyReport->uuid,
                            'daily_report_id' => $dailyReport->id,
                            'statusenabled' => true,
                            'jenis' => $value['jenis'] ?? null,
                            'subcont' => $value['subcont'] ?? null,
                            'nomor_unit' => $value['nomor_unit'] ?? null,
                            'area' => $value['area'] ?? null,
                            'keterangan' => $value['keterangan'] ?? null,
                            'is_draft' => $typeDraft,
                        ]
                    );
                }
            }


            //Insert & Update Catatan Pengawas
            if (!empty($request->catatan)) {
                foreach (json_decode($request->catatan, true) as $catatan) {
                    BBCatatanPengawas::updateOrCreate(
                        [
                            'daily_report_id' => $dailyReport->id,
                            'jam_start' => $catatan['start_catatan'] ?? null,
                            'jam_stop' => $catatan['end_catatan'] ?? null,
                            'keterangan' => $catatan['description_catatan'] ?? null,
                        ],
                        [
                            'uuid' => (string) Uuid::uuid4()->toString(),
                            'daily_report_uuid' => $dailyReport->uuid,
                            'daily_report_id' => $dailyReport->id,
                            'statusenabled' => true,
                            'jam_start' => $catatan['start_catatan'] ?? null,
                            'jam_stop' => $catatan['end_catatan'] ?? null,
                            'keterangan' => $catatan['description_catatan'] ?? null,
                            'is_draft' => $typeDraft,
                        ]
                    );
                }
            }
                return response()->json([
                    'success' => true,
                    'message' => 'Draft saved successfully!',
                    'uuid' => $dailyReport->uuid,
                    'data' => $dailyReport,
                ]);
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to save draft: ' . $th->getMessage()], 500);
        }
    }

    public function delete($uuid)
    {
        $daily = DB::table('BB_DAILY_REPORT as dr')->where('dr.uuid', $uuid)->first();

        $daily = DB::table('BB_DAILY_REPORT as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'us.name as pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',

        )->where('dr.uuid', $uuid)->first();

        // dd($daily);

        try {

            Log::create([
                'tanggal_loging' => now(),
                'jenis_loging' => 'Laporan Kerja',
                'nama_user' => Auth::user()->id,
                'nik' => Auth::user()->nik,
                'keterangan' => 'Hapus laporan kerja batu bara dengan PIC: '. $daily->pic . ', tanggal pembuatan: '. $daily->tanggal .
                ', shift: '. $daily->shift,
            ]);

            BBDailyReport::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            BBLoadingPoint::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            BBUnitSupport::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            BBCatatanPengawas::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Laporan kerja Batu Bara berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', $th->getMessage());
        }
    }
}
