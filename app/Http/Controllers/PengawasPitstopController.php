<?php

namespace App\Http\Controllers;

use App\Exports\PengawasPitstopExport;
use App\Models\Area;
use App\Models\Log;
use App\Models\Lokasi;
use App\Models\Personal;
use App\Models\PitstopReport;
use App\Models\PitstopReportDesc;
use App\Models\Shift;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PengawasPitstopController extends Controller
{
    //
    public function index()
    {
        $today = now()->toDateString();
        $daily = PitstopReport::where('foreman_id', Auth::user()->id)
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
            $daily['nik_supervisor'] = $daily['nik_supervisor'] . '|' . $daily['nama_supervisor'];

            if (!empty($daily['date'])) {
                $tanggalDasar = new DateTimeImmutable($daily['date']);
                $daily['date'] = $tanggalDasar ? $tanggalDasar->format('m/d/Y') : $daily['date'];
            }
        }

        $unitPitstops = [];

        if ($daily) {
            $unitPitstops = PitstopReportDesc::where('report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();

        }
        $unit = Unit::select('VHC_ID', 'VHC_ACTIVE')
            ->where('VHC_ACTIVE', 1)
            ->get();

        // $supervisor = Personal::select('ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY')->where('ROLETYPE', 3)->get();
        $supervisor = User::select(
            'nik as NRP',
            'name as PERSONALNAME',
            'role as JABATAN'
            )->where('role', 'SUPERVISOR')
            ->where('id', '!=', 95)
            ->where('statusenabled', true)->get();
        $shift = Shift::where('statusenabled', true)->get();
        $area = Area::where('statusenabled', true)->get();
        $operator = Personal::select('ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY')->where('ROLETYPE', 0)->get();

        $data = [
            'unit' => $unit,
            'supervisor' => $supervisor,
            'shift' => $shift,
            'area' => $area,
            'operator' => $operator,

        ];

        return view('pengawas-pitstop.index', compact('daily', 'data', 'unitPitstops'));
    }

   public function saveAsDraft(Request $request)
    {
        try {
            $typeDraft = $request->actionType === 'finish' ? false : true;
            $uuid = $request->uuid ?: Uuid::uuid4()->toString();

            // Ambil data lama kalau ada
            $dailyReport = PitstopReport::firstOrNew(['uuid' => $uuid]);

            // Supervisor
            $nikSupervisor = null;
            $namaSupervisor = null;
            if (!empty($request->nik_supervisor)) {
                [$nikSupervisor, $namaSupervisor] = explode('|', $request->nik_supervisor) + [null, null];
            } else {
                $nikSupervisor = $dailyReport->nik_supervisor ?? null;
                $namaSupervisor = $dailyReport->nama_supervisor ?? null;
            }

            // Data utama
            $data = [
                'uuid' => $uuid,
                'foreman_id' => Auth::id(),
                'statusenabled' => true,
                'date' => $request->filled('date')
                    ? now()->parse($request->date)->format('Y-m-d')
                    : null,
                'shift_id' => $request->shift_id,
                'area_id' => $request->area_id,
                'nik_supervisor' => $nikSupervisor,
                'nama_supervisor' => $namaSupervisor,
                'is_draft' => $typeDraft,
                'catatan_pengawas' => $request->catatan_pengawas,
            ];

            // Role SUPERVISOR
            if (Auth::user()->role === 'SUPERVISOR') {
                $data['nik_supervisor'] = Auth::user()->nik;
                $data['nama_supervisor'] = Auth::user()->name;
                // $data['catatan_verified_supervisor'] = $request->catatan_pitstop;
                $data['verified_supervisor'] = Auth::user()->nik;
            }

            // Role FOREMAN
            if (Auth::user()->role === 'FOREMAN') {
                $data['nik_foreman'] = Auth::user()->nik;
                $data['nama_foreman'] = Auth::user()->name;
                // $data['catatan_verified_foreman'] = $request->catatan_pitstop;
                $data['verified_foreman'] = Auth::user()->nik;
            }

            // Simpan report utama
            $dailyReport->fill($data);
            $dailyReport->save();

            // Simpan detail unit
            if (!empty($request->unit_pitstop)) {
                $unitSupports = json_decode($request->unit_pitstop, true);

                foreach ($unitSupports as $value) {
                    // Ubah string "null" jadi NULL
                    foreach ($value as $k => $v) {
                        if ($v === 'null') {
                            $value[$k] = null;
                        }
                    }

                    $statusUnitBreakdown = !empty($value['status_unit_breakdown'])
                    ? Carbon::parse($value['status_unit_breakdown'])->format('Y-m-d H:i:s')
                    : null;

                $statusUnitReady = !empty($value['status_unit_ready'])
                    ? Carbon::parse($value['status_unit_ready'])->format('Y-m-d H:i:s')
                    : null;

                $statusOprReady = !empty($value['status_opr_ready'])
                    ? Carbon::parse($value['status_opr_ready'])->format('Y-m-d H:i:s')
                    : null;

                $opr_settingan = $value['opr_settingan'] ?? null;
                if ($opr_settingan) {
                    $opr_parts = explode('|', $opr_settingan);
                    $nikOprSettingan = $opr_parts[0];
                    $namaOprSettingan = $opr_parts[1] ?? '';
                } else {
                    $nikOprSettingan = $namaOprSettingan = null;
                }

                $opr_ready = $value['opr_ready'] ?? null;
                if ($opr_ready) {
                    $opr_parts = explode('|', $opr_ready);
                    $nikOprReady = $opr_parts[0];
                    $namaOprReady = $opr_parts[1] ?? '';
                } else {
                    $nikOprReady = $namaOprReady = null;
                }

                    $desc = PitstopReportDesc::firstOrNew([
                        'uuid' => $value['uuid'] ?? Uuid::uuid4()->toString(),
                    ]);

                    $desc->fill([
                        'report_uuid' => $dailyReport->uuid,
                        'report_id' => $dailyReport->id,
                        'statusenabled' => true,
                        'no_unit' => $value['nomor_unit'] ?? null,
                        'opr_settingan' => $nikOprSettingan,  // Gunakan nik untuk opr_settingan
                        'nama_opr_settingan' => $namaOprSettingan,
                        'status_unit_breakdown' => $statusUnitBreakdown,
                        'status_unit_ready' => $statusUnitReady,
                        'status_opr_ready' => $statusOprReady,
                        'opr_ready' => $nikOprReady,
                        'nama_opr_ready' => $namaOprReady,
                        'keterangan' => $value['keterangan'] ?? null,
                        'is_draft' => $typeDraft,
                    ]);

                    $desc->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully!',
                'uuid' => $dailyReport->uuid,
                'data' => $dailyReport,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to save draft: ' . $th->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        session(['requestTimeLaporanKerjPitstop' => $request->all()]);

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


        $daily = DB::table('PITSTOP_REPORT as pr')
        ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
        ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
        ->select(
            'pr.id',
            'pr.uuid',
            'pr.created_at',
            'pr.date as tanggal',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'us.name as pic',
            'us.nik as nik_pic',
            'pr.nik_foreman',
            'us3.name as nama_foreman',
            'pr.nik_supervisor',
            'us4.name as nama_supervisor',
            'pr.nik_superintendent',
            'us5.name as nama_superintendent',
            'pr.is_draft',
            'pr.verified_supervisor',
            'pr.verified_superintendent',


        )
        ->whereBetween('pr.date', [$startTimeFormatted, $endTimeFormatted])
        ->where('pr.statusenabled', true);


        $daily = $daily->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('pr.nik_foreman', Auth::user()->nik)
                  ->orWhere('pr.nik_supervisor', Auth::user()->nik);
                //   ->orWhere('pr.nik_superintendent', Auth::user()->nik);
            }
        });

        // if (Auth::user()->role == 'FOREMAN') {
        //     $daily->where('pr.nik_foreman', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERVISOR') {
        //     $daily->where('pr.nik_supervisor', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERINTENDENT') {
        //     $daily->where('pr.nik_superintendent', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'ADMIN') {
        //     $daily->orWhere('pic', Auth::user()->id);
        // }

        $daily = $daily->get();

        // dd($daily);

        return view('pengawas-pitstop.daftar.index', compact('daily'));
    }

    public function preview($uuid)
    {
        $daily = DB::table('PITSTOP_REPORT as pr')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->select(
                'pr.id',
                'pr.uuid',
                'pr.created_at',
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.name as pic',
                'us.nik as nik_pic',
                'pr.nik_foreman',
                'pr.catatan_pengawas',
                'us3.name as nama_foreman',
                'pr.nik_supervisor',
                'us4.name as nama_supervisor',
                'pr.nik_superintendent',
                'us5.name as nama_superintendent',
                'pr.is_draft',
                'pr.verified_foreman',
                'pr.verified_supervisor',
                'pr.verified_superintendent'
            )
            ->where('pr.uuid', $uuid)
            ->where('pr.statusenabled', true)
            ->first();

        if(!$daily){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        // buat QR code
        $daily->verified_foreman = $daily->verified_foreman ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
        $daily->verified_supervisor = $daily->verified_supervisor ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
        $daily->verified_superintendent = $daily->verified_superintendent ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;

        // ambil shift harian untuk closure
        $dailyShift = $daily->shift;

        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->select(
                'prd.id',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                DB::raw("CONVERT(varchar, DATEADD(SECOND, DATEDIFF(SECOND, prd.status_unit_ready, prd.status_opr_ready), 0), 108) as durasi"),
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.report_uuid', $uuid)
            ->where('prd.statusenabled', true)
            ->get()
            ->map(function($sp) use ($dailyShift) { // pakai $dailyShift
                // cek beda opr
                $sp->isDifferentOpr = $sp->opr_settingan !== $sp->opr_ready;

                // cek shift breakdown
                if ($sp->status_unit_breakdown) {
                    $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                    $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                    $sp->isOutsideShift = $dailyShift !== $shiftFromTime;
                    $sp->time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
                } else {
                    $sp->isOutsideShift = false;
                    $sp->time_breakdown = '';
                }

                // durasi efektif (kurangi jam istirahat 12:00-13:00)
                if ($sp->status_unit_ready && $sp->status_opr_ready) {
                    $start = strtotime($sp->status_unit_ready);
                    $end = strtotime($sp->status_opr_ready);

                    $totalMinutes = ($end - $start) / 60;

                    $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                    $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                    $overlapStart = max($start, $breakStart);
                    $overlapEnd = min($end, $breakEnd);
                    $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart)/60 : 0;

                    $totalMinutes -= $breakMinutes;
                    $sp->totalMinutes = $totalMinutes;
                    $sp->durasi_eff = gmdate('H:i:s', $totalMinutes*60);
                } else {
                    $sp->totalMinutes = 0;
                    $sp->durasi_eff = '00:00';
                }

                // format jam ready
                $sp->status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
                $sp->status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

                return $sp;
            });

        $data = [
            'daily' => $daily,
            'dailyDesc' => $dailyDesc,
        ];

        return view('pengawas-pitstop.preview', compact('data'));
    }

    public function cetak($uuid)
    {
        $daily = DB::table('PITSTOP_REPORT as pr')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->select(
                'pr.id',
                'pr.uuid',
                'pr.created_at',
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.name as pic',
                'us.nik as nik_pic',
                'pr.nik_foreman',
                'pr.catatan_pengawas',
                'us3.name as nama_foreman',
                'pr.nik_supervisor',
                'us4.name as nama_supervisor',
                'pr.nik_superintendent',
                'us5.name as nama_superintendent',
                'pr.is_draft',
                'pr.verified_foreman',
                'pr.verified_supervisor',
                'pr.verified_superintendent'
            )
            ->where('pr.uuid', $uuid)
            ->where('pr.statusenabled', true)
            ->first();

        if(!$daily){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        // buat QR code
        $daily->verified_foreman = $daily->verified_foreman ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
        $daily->verified_supervisor = $daily->verified_supervisor ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
        $daily->verified_superintendent = $daily->verified_superintendent ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;

        // ambil shift harian untuk closure
        $dailyShift = $daily->shift;

        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->select(
                'prd.id',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                DB::raw("CONVERT(varchar, DATEADD(SECOND, DATEDIFF(SECOND, prd.status_unit_ready, prd.status_opr_ready), 0), 108) as durasi"),
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.report_uuid', $uuid)
            ->where('prd.statusenabled', true)
            ->get()
            ->map(function($sp) use ($dailyShift) { // pakai $dailyShift
                // cek beda opr
                $sp->isDifferentOpr = $sp->opr_settingan !== $sp->opr_ready;

                // cek shift breakdown
                if ($sp->status_unit_breakdown) {
                    $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                    $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                    $sp->isOutsideShift = $dailyShift !== $shiftFromTime;
                    $sp->time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
                } else {
                    $sp->isOutsideShift = false;
                    $sp->time_breakdown = '';
                }

                // durasi efektif (kurangi jam istirahat 12:00-13:00)
                if ($sp->status_unit_ready && $sp->status_opr_ready) {
                    $start = strtotime($sp->status_unit_ready);
                    $end = strtotime($sp->status_opr_ready);

                    $totalMinutes = ($end - $start) / 60;

                    $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                    $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                    $overlapStart = max($start, $breakStart);
                    $overlapEnd = min($end, $breakEnd);
                    $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart)/60 : 0;

                    $totalMinutes -= $breakMinutes;
                    $sp->totalMinutes = $totalMinutes;
                    $sp->durasi_eff = gmdate('H:i:s', $totalMinutes*60);
                } else {
                    $sp->totalMinutes = 0;
                    $sp->durasi_eff = '00:00';
                }

                // format jam ready
                $sp->status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
                $sp->status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

                return $sp;
            });

        $data = [
            'daily' => $daily,
            'dailyDesc' => $dailyDesc,
        ];

        return view('pengawas-pitstop.cetak', compact('data'));
    }

    public function download($uuid)
    {
        $daily = DB::table('PITSTOP_REPORT as pr')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->select(
                'pr.id',
                'pr.uuid',
                'pr.created_at',
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.name as pic',
                'us.nik as nik_pic',
                'pr.nik_foreman',
                'pr.catatan_pengawas',
                'us3.name as nama_foreman',
                'pr.nik_supervisor',
                'us4.name as nama_supervisor',
                'pr.nik_superintendent',
                'us5.name as nama_superintendent',
                'pr.is_draft',
                'pr.verified_foreman',
                'pr.verified_supervisor',
                'pr.verified_superintendent'
            )
            ->where('pr.uuid', $uuid)
            ->where('pr.statusenabled', true)
            ->first();

        if(!$daily){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        // buat QR code
        $daily->verified_foreman = $daily->verified_foreman ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
        $daily->verified_supervisor = $daily->verified_supervisor ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
        $daily->verified_superintendent = $daily->verified_superintendent ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;

        // ambil shift harian untuk closure
        $dailyShift = $daily->shift;

        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->select(
                'prd.id',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                DB::raw("CONVERT(varchar, DATEADD(SECOND, DATEDIFF(SECOND, prd.status_unit_ready, prd.status_opr_ready), 0), 108) as durasi"),
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.report_uuid', $uuid)
            ->where('prd.statusenabled', true)
            ->get()
            ->map(function($sp) use ($dailyShift) { // pakai $dailyShift
                // cek beda opr
                $sp->isDifferentOpr = $sp->opr_settingan !== $sp->opr_ready;

                // cek shift breakdown
                if ($sp->status_unit_breakdown) {
                    $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                    $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                    $sp->isOutsideShift = $dailyShift !== $shiftFromTime;
                    $sp->time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
                } else {
                    $sp->isOutsideShift = false;
                    $sp->time_breakdown = '';
                }

                // durasi efektif (kurangi jam istirahat 12:00-13:00)
                if ($sp->status_unit_ready && $sp->status_opr_ready) {
                    $start = strtotime($sp->status_unit_ready);
                    $end = strtotime($sp->status_opr_ready);

                    $totalMinutes = ($end - $start) / 60;

                    $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                    $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                    $overlapStart = max($start, $breakStart);
                    $overlapEnd = min($end, $breakEnd);
                    $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart)/60 : 0;

                    $totalMinutes -= $breakMinutes;
                    $sp->totalMinutes = $totalMinutes;
                    $sp->durasi_eff = gmdate('H:i:s', $totalMinutes*60);
                } else {
                    $sp->totalMinutes = 0;
                    $sp->durasi_eff = '00:00';
                }

                // format jam ready
                $sp->status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
                $sp->status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

                return $sp;
            });

        $data = [
            'daily' => $daily,
            'dailyDesc' => $dailyDesc,
        ];

        $pdf = PDF::loadView('pengawas-pitstop.download', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan Harian Pengawas Pitstop.pdf');
    }


    public function delete($uuid)
    {
        $daily = PitstopReport::where('uuid', $uuid)->first();

        try {

            Log::create([
                'tanggal_loging' => now(),
                'jenis_loging' => 'Laporan Kerja',
                'nama_user' => Auth::user()->id,
                'nik' => Auth::user()->nik,
                'keterangan' => 'Hapus laporan kerja pitstop dengan PIC: '. $daily->pic . ', tanggal pembuatan: '. $daily->tanggal .
                ', shift: '. $daily->shift,
            ]);

            PitstopReport::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            PitstopReportDesc::where('report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Laporan kerja Pitstop berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', $th->getMessage());
        }
    }


    public function post(Request $request)
    {

        $shift = Shift::where('id', $request->shift_id)->first();

        Log::create([
            'tanggal_loging' => now(),
            'jenis_loging' => 'Laporan Kerja',
            'nama_user' => Auth::user()->id,
            'nik' => Auth::user()->nik,
            'keterangan' => 'Tambah laporan kerja pitstop dengan nama: '. Auth::user()->name . ', NIK: '. Auth::user()->nik . ', Role: '. Auth::user()->role .
            ', shift: '. $shift->keterangan
        ]);
        // dd($request->all());
        try {
            return DB::transaction(function () use ($request) {

                    PitstopReport::where('uuid', $request->uuid)->update(['is_draft' => false]);
                    PitstopReportDesc::where('report_uuid', $request->uuid)->update(['is_draft' => false]);
            return redirect()->route('pengawas-pitstop.show')->with('success', 'Laporan berhasil dibuat');
            });
        } catch (\Throwable $th) {
           return redirect()->route('pengawas-pitstop.index')->with('info', 'Laporan gagal dibuat.. \n' . $th->getMessage());
        }
    }

    public function destroyPitstop($id)
    {
        try {
            PitstopReportDesc::findOrFail($id)->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data', 'error' => $e->getMessage()], 500);
        }
    }

    public function verifiedAll($uuid)
    {
        $klkh =  PitstopReport::where('uuid', $uuid)->first();

        try {
            PitstopReport::where('id', $klkh->id)->update([
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
        $klkh =  PitstopReport::where('uuid', $uuid)->first();

        try {
            PitstopReport::where('id', $klkh->id)->update([
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
        $klkh =  PitstopReport::where('uuid', $uuid)->first();

        try {
            PitstopReport::where('id', $klkh->id)->update([
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
        $klkh =  PitstopReport::where('uuid', $uuid)->first();

        try {
            PitstopReport::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function operator(Request $request)
    {
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

        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->select(
                'prd.id',
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.name as pic',
                'us.nik as nik_pic',
                'pr.nik_foreman',
                'pr.catatan_pengawas',
                'us3.name as nama_foreman',
                'pr.nik_supervisor',
                'us4.name as nama_supervisor',
                'pr.nik_superintendent',
                'us5.name as nama_superintendent',
                'pr.is_draft',
                'pr.verified_foreman',
                'pr.verified_supervisor',
                'pr.verified_superintendent',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                DB::raw("CONVERT(varchar, DATEADD(SECOND, DATEDIFF(SECOND, prd.status_unit_ready, prd.status_opr_ready), 0), 108) as durasi"),
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->whereBetween('pr.date', [$startTimeFormatted, $endTimeFormatted]);

        // filter role selain ADMIN
        if (Auth::user()->role !== 'ADMIN') {
            $dailyDesc->where('pr.nik_foreman', Auth::user()->id);
        }

        // ambil shift dari variabel global supaya bisa dipakai di closure
        $shift = $dailyShift->shift ?? null;

        $dailyDesc = $dailyDesc->get()->map(function ($sp) use ($shift) {
            // cek beda opr
            $sp->isDifferentOpr = $sp->opr_settingan !== $sp->opr_ready;

            // cek shift breakdown
            if ($sp->status_unit_breakdown) {
                $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                $sp->isOutsideShift = $shift !== $shiftFromTime;
                $sp->time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
            } else {
                $sp->isOutsideShift = false;
                $sp->time_breakdown = '';
            }

            // durasi efektif (kurangi jam istirahat 12:00-13:00)
            if ($sp->status_unit_ready && $sp->status_opr_ready) {
                $start = strtotime($sp->status_unit_ready);
                $end   = strtotime($sp->status_opr_ready);

                $totalMinutes = ($end - $start) / 60;

                $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                $overlapStart = max($start, $breakStart);
                $overlapEnd   = min($end, $breakEnd);
                $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart) / 60 : 0;

                $totalMinutes -= $breakMinutes;
                $sp->totalMinutes = $totalMinutes;
                $sp->durasi_eff = gmdate('H:i:s', $totalMinutes * 60);
            } else {
                $sp->totalMinutes = 0;
                $sp->durasi_eff = '00:00';
            }

            // format jam ready
            $sp->status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
            $sp->status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

            return $sp;
        });



        return view('pengawas-pitstop.operator', compact('dailyDesc'));
    }

    public function operatorAPI(Request $request)
    {
        session(['requestTimeLaporanKerjPitstop' => $request->all()]);

        if (empty($request->rangeStart) || empty($request->rangeEnd)) {
            $time = new DateTime();
            $startDate = new DateTime($time->format('Y-m-d'));
            $endDate   = new DateTime($time->format('Y-m-d'));
        } else {
            $startDate = new DateTime($request->rangeStart);
            $endDate   = new DateTime($request->rangeEnd);
        }

        $startTimeFormatted = $startDate->format('Y-m-d');
        $endTimeFormatted   = $endDate->format('Y-m-d');

        // Pagination params dari datatables
        $offset = $request->input('start', 0);
        $limit  = $request->input('length', 10);
        $draw   = $request->input('draw');

        $dailyDesc = DB::table('PITSTOP_REPORT_DESC as prd')
            ->leftJoin('PITSTOP_REPORT as pr', 'prd.report_id', '=', 'pr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as vhc', 'prd.no_unit', '=', 'vhc.VHC_ID')
            ->leftJoin('REF_SHIFT as sh', 'pr.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'pr.area_id', '=', 'ar.id')
            ->leftJoin('users as us', 'pr.foreman_id', '=', 'us.id')
            ->leftJoin('users as us3', 'pr.nik_foreman', '=', 'us3.nik')
            ->leftJoin('users as us4', 'pr.nik_supervisor', '=', 'us4.nik')
            ->leftJoin('users as us5', 'pr.nik_superintendent', '=', 'us5.nik')
            ->select(
                'prd.id',
                'pr.date as tanggal',
                'sh.keterangan as shift',
                'ar.keterangan as area',
                'us.name as pic',
                'us.nik as nik_pic',
                'pr.nik_foreman',
                'pr.catatan_pengawas',
                'us3.name as nama_foreman',
                'pr.nik_supervisor',
                'us4.name as nama_supervisor',
                'pr.nik_superintendent',
                'us5.name as nama_superintendent',
                'pr.is_draft',
                'pr.verified_foreman',
                'pr.verified_supervisor',
                'pr.verified_superintendent',
                DB::raw("LEFT(prd.no_unit, 2) as jenis_unit"),
                'vhc.EQU_TYPEID as type_unit',
                DB::raw("SUBSTRING(prd.no_unit, 3, LEN(prd.no_unit)) as no_unit"),
                'prd.opr_settingan',
                'prd.nama_opr_settingan',
                'prd.status_unit_breakdown',
                'prd.status_unit_ready',
                'prd.status_opr_ready',
                DB::raw("CONVERT(varchar, DATEADD(SECOND, DATEDIFF(SECOND, prd.status_unit_ready, prd.status_opr_ready), 0), 108) as durasi"),
                'prd.opr_ready',
                'prd.nama_opr_ready',
                'prd.keterangan'
            )
            ->where('prd.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->whereBetween('pr.date', [$startTimeFormatted, $endTimeFormatted]);

        // filter role selain ADMIN
        if (Auth::user()->role !== 'ADMIN') {
            $dailyDesc->where('pr.nik_foreman', Auth::user()->id);
        }

        // search filter
        if ($request->search['value']) {
            $searchValue = '%' . $request->search['value'] . '%';
            $dailyDesc->where(function($query) use ($searchValue) {
                $query->orWhere('prd.no_unit', 'like', $searchValue);
            });
        }

        // hitung total sebelum pagination
        $filteredRecords = $dailyDesc->count();

        // ambil data dengan pagination
        $data = $dailyDesc->skip($offset)->take($limit)->get();

        // post-processing (map)
        $data = $data->map(function ($sp) {
            $sp->isDifferentOpr = $sp->opr_settingan !== $sp->opr_ready;

            // cek shift breakdown
            if ($sp->status_unit_breakdown) {
                $hour = (int) date('H', strtotime($sp->status_unit_breakdown));
                $shiftFromTime = ($hour >= 7 && $hour < 19) ? 'Siang' : 'Malam';
                $sp->isOutsideShift = $sp->shift !== $shiftFromTime;
                $sp->time_breakdown = date('H:i:s', strtotime($sp->status_unit_breakdown));
            } else {
                $sp->isOutsideShift = false;
                $sp->time_breakdown = '';
            }

            // durasi efektif (kurangi jam istirahat 12:00-13:00)
            if ($sp->status_unit_ready && $sp->status_opr_ready) {
                $start = strtotime($sp->status_unit_ready);
                $end   = strtotime($sp->status_opr_ready);

                $totalMinutes = ($end - $start) / 60;

                $breakStart = strtotime(date('Y-m-d', $start).' 12:00:00');
                $breakEnd   = strtotime(date('Y-m-d', $start).' 13:00:00');

                $overlapStart = max($start, $breakStart);
                $overlapEnd   = min($end, $breakEnd);
                $breakMinutes = ($overlapEnd > $overlapStart) ? ($overlapEnd - $overlapStart) / 60 : 0;

                $totalMinutes -= $breakMinutes;
                $sp->totalMinutes = $totalMinutes;
                $sp->durasi_eff = gmdate('H:i:s', $totalMinutes * 60);
            } else {
                $sp->totalMinutes = 0;
                $sp->durasi_eff = '00:00';
            }

            $sp->status_unit_ready_fmt = $sp->status_unit_ready ? date('H:i:s', strtotime($sp->status_unit_ready)) : '';
            $sp->status_opr_ready_fmt  = $sp->status_opr_ready ? date('H:i:s', strtotime($sp->status_opr_ready)) : '';

            return $sp;
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $filteredRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function excel(Request $request)
    {

        if (empty(session('requestTimeLaporanKerjPitstop')['rangeStart']) || empty(session('requestTimeLaporanKerjPitstop')['rangeEnd'])){
            $time = new DateTime();
            $start = $time;
            $end = $time;

        }else{
            $start = new DateTime(session('requestTimeLaporanKerjPitstop')['rangeStart']);
            $end = new DateTime(session('requestTimeLaporanKerjPitstop')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');

        // dd($bulan);
        return Excel::download(new PengawasPitstopExport($startTimeFormatted, $endTimeFormatted), 'Laporan Pengawas Pitstop.xlsx');
    }

}
