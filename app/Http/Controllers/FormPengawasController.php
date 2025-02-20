<?php

namespace App\Http\Controllers;

use App\Models\AlatSupport;
use App\Models\Area;
use App\Models\CatatanPengawas;
use App\Models\DailyReport;
use App\Models\FrontLoading;
use App\Models\Lokasi;
use App\Models\Material;
use App\Models\Personal;
use App\Models\Shift;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormPengawasController extends Controller
{
    public function index()
    {
        $daily = DailyReport::where('foreman_id', Auth::user()->id)
            ->where('is_draft', true)
            ->first();

        $frontLoading = [];
        $alatSupports = [];
        $supervisorNotes = [];

        if ($daily) {
            $frontLoading = FrontLoading::where('daily_report_id', $daily->id)->get();
            $alatSupports = AlatSupport::where('daily_report_id', $daily->id)->get();
            $supervisorNotes = CatatanPengawas::where('daily_report_id', $daily->id)->get();
        }

        $data = [
            'operator' => Personal::where('ROLETYPE', 0)->get(),
            'supervisor' => Personal::where('ROLETYPE', 3)->get(),
            'superintendent' => Personal::where('ROLETYPE', 4)->get(),
            'lokasi' => Lokasi::where('statusenabled', true)->get(),
            'area' => Area::where('statusenabled', true)->get(),
            'shift' => Shift::where('statusenabled', true)->get(),
            'EX' => Unit::select(['VHC_ID', 'VHC_TYPEID', 'VHC_GROUPID', 'VHC_ACTIVE'])
                ->where('VHC_ID', 'like', 'EX%')
                ->where('VHC_ACTIVE', true)
                ->get(),
            'nomor_unit' => Unit::select('VHC_ID')
            ->where('VHC_ID', 'NOT LIKE', 'HD%')
            ->get(),
        ];

        return view('form-pengawas.index', compact('data', 'daily', 'frontLoading', 'alatSupports', 'supervisorNotes'));
    }

    //
//    public function index()
//    {
//
//        $daily = DailyReport::where('foreman_id', Auth::user()->id)
//        ->whereDate('created_at', now())
//            ->draft()
//        ->get();
//
//        // if(empty($daily)){
//        //     return view('form-pengawas.empty');
//        // }
//        $ex = Unit::select([
//            'VHC_ID',
//            'VHC_TYPEID',
//            'VHC_GROUPID',
//            'VHC_ACTIVE',
//        ])
//            ->where('VHC_ID', 'like', 'EX%')
//            ->where('VHC_ACTIVE', true)
//            ->get();
//
//        $nomor_unit = Unit::select('VHC_ID')
//            ->where('VHC_ID', 'NOT LIKE', 'HD%')
//            ->get();
//
//        $operator = Personal::select
//        (
//            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
//        )->where('ROLETYPE', 0)->get();
//
//        $supervisor = Personal::select
//        (
//            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
//        )->where('ROLETYPE', 3)->get();
//
//        $superintendent = Personal::select
//        (
//            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
//        )->where('ROLETYPE', 4)->get();
//
//        $lokasi = Lokasi::where('statusenabled', true)->get();
//        $area = Area::where('statusenabled', true)->get();
//        $shift = Shift::where('statusenabled', true)->get();
//
//
//        $data = [
//            'operator' => $operator,
//            'supervisor' => $supervisor,
//            'superintendent' => $superintendent,
//            'EX' => $ex,
//            'EX' => $ex,
//            'nomor_unit' => $nomor_unit,
//            'lokasi' => $lokasi,
//            'area' => $area,
//            'shift' => $shift,
//        ];
//        return view('form-pengawas.index', compact('data', 'daily'));
//    }

    public function users(Request $request)
    {
        $nik = $request->query('nik');

        $data['users'] = Personal::where('ROLETYPE', 2)->get();

        // Mencari user berdasarkan NIK
        $user = $data['users']->firstWhere('NRP', $nik);

        if ($user) {
            return Response::json([
                'success' => true,
                'name' => $user->PERSONALNAME,
                'by' => 'ahmadfadillah'
            ]);
        } else {
            return Response::json([
                'success' => false,
                'message' => 'User tidak ditemukan',
                'by' => 'ahmadfadillah'
            ]);
        }
    }

    public function post(Request $request)
    {

        // dd($request->all());
        try {
            return DB::transaction(function () use ($request) {
                $draft = DailyReport::where('uuid', $request->uuid)
                    ->orWhere(function ($query) {
                        $query->where('foreman_id', Auth::id())->where('is_draft', true);
                    })
                    ->first();

                if ($draft['is_draft']) {
                        DailyReport::where('uuid', $request->uuid)->update([
                            'is_draft' => false,
                            'updated_by' => Auth::user()->id,
                        ]);
                        FrontLoading::where('daily_report_uuid', $request->uuid)->update([
                            'is_draft' => false,
                            'updated_by' => Auth::user()->id,
                        ]);
                        AlatSupport::where('daily_report_uuid', $request->uuid)->update([
                            'is_draft' => false,
                            'updated_by' => Auth::user()->id,
                        ]);
                        CatatanPengawas::where('daily_report_uuid', $request->uuid)->update([
                            'is_draft' => false,
                            'updated_by' => Auth::user()->id,
                        ]);
                }
                return redirect()->route('form-pengawas.show')->with('success', 'Laporan berhasil dibuat');
            });
        } catch (\Throwable $th) {
            return redirect()->route('form-pengawas.show')->with('info', 'Laporan gagal dibuat.. \n' . $th->getMessage());
        }
    }

    public function getOperatorByNIK($nik)
    {
        // Data operator
        $data = Personal::select(
            'NRP as MAT_ID',
            'PERSONALNAME as MAT_DESC',
            'ROLETYPE as MAT_CATEGORY',
        )
        ->where('ROLETYPE', 0)->get();

        // Cari operator berdasarkan NIK
        $operator = $data->firstWhere('MAT_ID', $nik);

        if ($operator) {
            return response()->json([
                'success' => true,
                'nama' => $operator->MAT_DESC,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Operator tidak ditemukan',
            ]);
        }
    }

    public function show(Request $request)
    {
        session(['requestTimeLaporanKerja' => $request->all()]);

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


        $daily = DB::table('daily_report_t as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
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

        return view('form-pengawas.daftar.index', compact('daily'));
    }

    public function preview($uuid)
    {
        $daily = DB::table('daily_report_t as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.uuid',
            'dr.foreman_id as pic',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
            'us.nik as nik_foreman',
            'us.name as nama_foreman',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
        )->where('dr.uuid', $uuid)->first();

        if($daily == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $daily->verified_foreman = $daily->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;
        }

        $front = DB::table('front_loading_t as fl')
        ->leftJoin('daily_report_t as dr', 'fl.daily_report_id', '=', 'dr.id')
        ->leftJoin('focus.dbo.FLT_VEHICLE as flt', 'fl.nomor_unit', '=', 'flt.VHC_ID')
        ->select(
            'fl.nomor_unit',
            'flt.EQU_GROUPID as type',
            DB::raw("CASE
                    WHEN flt.EQU_GROUPID LIKE 'HT%' THEN 'Hitachi'
                    WHEN flt.EQU_GROUPID LIKE 'PC%' THEN 'Komatsu'
                    ELSE 'Unknown'
                END as brand"),
            'fl.siang',
            'fl.malam',
            'fl.checked',
            'fl.keterangan',
        )
        ->where('fl.statusenabled', true)
        ->where('fl.daily_report_uuid', $uuid)
        ->get()
        ->groupBy('brand');

        $support = DB::table('alat_support_t as al')
        ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'al.shift_operator_id', '=', 'sh.id')
        ->select(
            'al.alat_unit as nomor_unit',
            'al.nama_operator',
            'al.hm_awal',
            'al.hm_akhir',
            'al.hm_cash',
            'al.keterangan',
            'sh.keterangan as shift',
            'al.tanggal_operator as tanggal',
        )
        ->where('al.daily_report_uuid', $uuid)->get();

        $catatan = DB::table('catatan_pengawas_t as cp')
        ->leftJoin('daily_report_t as dr', 'cp.daily_report_id', '=', 'dr.id')
        ->select(
            'cp.jam_start',
            'cp.jam_stop',
            'cp.keterangan',
        )
        ->where('cp.daily_report_uuid', $uuid)->get();

        $timeSlots = [
            'siang' => ['07.00 - 08.00', '08.00 - 09.00', '09.00 - 10.00', '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00', '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00'],
            // 'malam' => ['19.00 - 20.00', '20.00 - 21.00', '21.00 - 22.00', '22.00 - 23.00', '23.00 - 24.00', '24.00 - 01.00', '01.00 - 02.00', '02.00 - 03.00', '03.00 - 04.00', '04.00 - 05.00', '05.00 - 06.00', '06.00 - 07.00'],
        ];

        // Menghasilkan data seperti '✓' untuk menandakan waktu yang dicentang
        $processedData = $front->map(function ($units, $brand) use ($timeSlots) {
            return $units->map(function ($unit) use ($timeSlots) {
                $siangTimes = json_decode($unit->siang, true);
                $malamTimes = json_decode($unit->malam, true);
                $checked = array_map(function ($item) {
                    return $item === true; // Convert true string to boolean
                }, json_decode($unit->checked, true));
                $keterangan = array_map(function ($item) {
                    return $item === null ? '' : $item; // Mengganti null dengan string kosong
                }, json_decode($unit->keterangan, true));

                $siangResult = collect($timeSlots['siang'])->map(function ($slot) use ($siangTimes, $checked, $keterangan) {
                    $index = array_search($slot, $siangTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'status' => '√', // Checkmark
                            'keterangan' => $keterangan[$index] ?? '', // Get corresponding keterangan
                        ];
                    }
                    return (object)[
                        'status' => '',
                        'keterangan' => '', // No keterangan
                    ];
                });
                return [
                    'brand' => $unit->brand,
                    'type' => $unit->type,
                    'nomor_unit' => $unit->nomor_unit,
                    'siang' => $siangResult,
                    // 'malam' => $malamResult,
                ];
            });
        });

        $data = [
            'daily' => $daily,
            'front' => $processedData,
            'support' => $support,
            'catatan' => $catatan,
        ];

        return view('form-pengawas.preview', compact(['data', 'timeSlots']));
    }

    public function download($uuid)
    {

        $daily = DB::table('daily_report_t as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'dr.uuid',
            'dr.foreman_id as pic',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
            'us.nik as nik_foreman',
            'us.name as nama_foreman',
            'dr.nik_foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dr.verified_foreman',
            'dr.verified_supervisor',
            'dr.verified_superintendent',
        )->where('dr.uuid', $uuid)->first();

        if($daily == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $daily->verified_foreman = $daily->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent) : null;
        }

        $front = DB::table('front_loading_t as fl')
        ->leftJoin('daily_report_t as dr', 'fl.daily_report_id', '=', 'dr.id')
        ->leftJoin('focus.dbo.FLT_VEHICLE as flt', 'fl.nomor_unit', '=', 'flt.VHC_ID')
        ->select(
            'fl.nomor_unit',
            'flt.EQU_GROUPID as type',
            DB::raw("CASE
                    WHEN flt.EQU_GROUPID LIKE 'HT%' THEN 'Hitachi'
                    WHEN flt.EQU_GROUPID LIKE 'PC%' THEN 'Komatsu'
                    ELSE 'Unknown'
                END as brand"),
            'fl.siang',
            'fl.malam',
            'fl.checked',
            'fl.keterangan',
        )
        ->where('fl.statusenabled', true)
        ->where('fl.daily_report_uuid', $uuid)
        ->get()
        ->groupBy('brand');

        $support = DB::table('alat_support_t as al')
        ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'al.shift_operator_id', '=', 'sh.id')
        ->select(
            'al.alat_unit as nomor_unit',
            'al.nama_operator',
            'al.hm_awal',
            'al.hm_akhir',
            'al.hm_cash',
            'al.keterangan',
            'sh.keterangan as shift',
            'al.tanggal_operator as tanggal',
        )
        ->where('al.daily_report_uuid', $uuid)->get();

        $catatan = DB::table('catatan_pengawas_t as cp')
        ->leftJoin('daily_report_t as dr', 'cp.daily_report_id', '=', 'dr.id')
        ->select(
            'cp.jam_start',
            'cp.jam_stop',
            'cp.keterangan',
        )
        ->where('cp.daily_report_uuid', $uuid)->get();

        $timeSlots = [
            'siang' => ['07.00 - 08.00', '08.00 - 09.00', '09.00 - 10.00', '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00', '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00'],
            // 'malam' => ['19.00 - 20.00', '20.00 - 21.00', '21.00 - 22.00', '22.00 - 23.00', '23.00 - 24.00', '24.00 - 01.00', '01.00 - 02.00', '02.00 - 03.00', '03.00 - 04.00', '04.00 - 05.00', '05.00 - 06.00', '06.00 - 07.00'],
        ];

        // Menghasilkan data seperti '✓' untuk menandakan waktu yang dicentang
        $processedData = $front->map(function ($units, $brand) use ($timeSlots) {
            return $units->map(function ($unit) use ($timeSlots) {
                $siangTimes = json_decode($unit->siang, true);
                $malamTimes = json_decode($unit->malam, true);
                $checked = array_map(function ($item) {
                    return $item === true; // Convert true string to boolean
                }, json_decode($unit->checked, true));
                $keterangan = array_map(function ($item) {
                    return $item === null ? '' : $item; // Mengganti null dengan string kosong
                }, json_decode($unit->keterangan, true));

                $siangResult = collect($timeSlots['siang'])->map(function ($slot) use ($siangTimes, $checked, $keterangan) {
                    $index = array_search($slot, $siangTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'status' => '√', // Checkmark
                            'keterangan' => $keterangan[$index] ?? '', // Get corresponding keterangan
                        ];
                    }
                    return (object)[
                        'status' => '',
                        'keterangan' => '', // No keterangan
                    ];
                });
                return [
                    'brand' => $unit->brand,
                    'type' => $unit->type,
                    'nomor_unit' => $unit->nomor_unit,
                    'siang' => $siangResult,
                    // 'malam' => $malamResult,
                ];
            });
        });

        $data = [
            'daily' => $daily,
            'front' => $processedData,
            'support' => $support,
            'catatan' => $catatan,
        ];

        // $pdf = PDF::loadView('form-pengawas.download', array(
        //     'data' => $data,
        // ))->setPaper('a4', 'portrait');
        // return $pdf->download($data['daily']->tanggal.'_'.$data['daily']->nik_foreman.'_'.$data['daily']->nama_foreman.'.pdf');

        return view('form-pengawas.download', compact(['data', 'timeSlots']));
    }

    public function autoSave(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validate([
                    'uuid' => 'nullable|string',
                    'tanggal_dasar' => 'nullable|date_format:m/d/Y',
                    'shift_dasar' => 'nullable|string',
                    'area' => 'nullable|string',
                    'lokasi' => 'nullable|string',
                    'nik_supervisor' => 'nullable|string',
                    'nama_supervisor' => 'nullable|string',
                    'nik_superintendent' => 'nullable|string',
                    'nama_superintendent' => 'nullable|string',
                ]);

                $supervisor = $request->nik_supervisor ?? '';
                $superintendent = $request->nik_superintendent ?? '';

                $nikSupervisor = $namaSupervisor = null;
                if ($supervisor) {
                    $nikSlotsSV = explode('|', $supervisor);
                    $nikSupervisor = $nikSlotsSV[0];
                    $namaSupervisor = trim($nikSlotsSV[1] ?? '');
                }

                $nikSuperintendent = $namaSuperintendent = null;
                if ($superintendent) {
                    $nikSlotsSI = explode('|', $superintendent);
                    $nikSuperintendent = $nikSlotsSI[0];
                    $namaSuperintendent = trim($nikSlotsSI[1] ?? '');
                }

                $tanggalDasar = null;
                if ($request->filled('tanggal_dasar')) {
                    try {
                        $tanggalDasar = Carbon::createFromFormat('m/d/Y', $request->tanggal_dasar)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return response()->json([
                            'message' => 'Format tanggal salah',
                            'error' => $e->getMessage(),
                        ], 422);
                    }
                }

                // Siapkan data untuk disimpan
                $dataToSave = [
                    'tanggal_dasar' => $tanggalDasar,
                    'shift_dasar_id' => $request->shift_dasar,
                    'area_id' => $request->area,
                    'lokasi_id' => $request->lokasi,
                    'nik_supervisor' => $nikSupervisor,
                    'nama_supervisor' => $namaSupervisor,
                    'nik_superintendent' => $nikSuperintendent,
                    'nama_superintendent' => $namaSuperintendent,
                    'statusenabled' => true,
                    'is_draft' => true,
                ];

                // Tambahkan data berdasarkan role
                if (Auth::user()->role == 'SUPERVISOR') {
                    $dataToSave['nik_supervisor'] = Auth::user()->nik;
                    $dataToSave['nama_supervisor'] = Auth::user()->name;
                    $dataToSave['verified_supervisor'] = Auth::user()->nik;
                }
                if (Auth::user()->role == 'FOREMAN') {
                    $dataToSave['nik_foreman'] = Auth::user()->nik;
                    $dataToSave['nama_foreman'] = Auth::user()->name;
                    $dataToSave['verified_foreman'] = Auth::user()->nik;
                }

                $draft = DailyReport::where('uuid', $request->uuid)
                    ->orWhere(function ($query) {
                        $query->where('foreman_id', Auth::id())->where('is_draft', true);
                    })
                    ->first();

                if ($draft) {
                    $draft->update($dataToSave);
                } else {
                    $dataToSave['uuid'] = $request->uuid ?? Uuid::uuid4()->toString();
                    $dataToSave['foreman_id'] = Auth::id();
                    $draft = DailyReport::create($dataToSave);
                }

                // Proses front_loading jika ada
                if (!empty($request->front_loading)) {
                    foreach ($request->front_loading as $front_unit) {
                        $timeData = isset($front_unit["time"]) && is_array($front_unit["time"]) ? $front_unit["time"] : [];

                        if (!is_array($timeData)) {
                            throw new \Exception('Format data time tidak valid');
                        }

                        $morning = [];
                        $night = [];
                        $checked = [];
                        $keterangan = [];

                        foreach ($timeData as $time) {
                            if (isset($time['value']) && ($time['checked'] == true || !empty($time['keterangan']))) {
                                $timeSlots = explode('|', $time['value']);

                                if (isset($timeSlots[0])) {
                                    $morning[] = trim($timeSlots[0]);
                                }
                                if (isset($timeSlots[1])) {
                                    $night[] = trim($timeSlots[1]);
                                }

                                $checked[] = $time['checked'] == "false" ? null : $time['checked'];
                                $keterangan[] = $time['keterangan'] ?? null;
                            }
                        }

                        if (!empty($checked)) {
                            FrontLoading::updateOrCreate(
                                [
                                    'daily_report_id' => $draft->id,
                                    'nomor_unit' => $front_unit["nomor_unit"],
                                ],
                                [
                                    'uuid' => $front_unit["uuid"] ?? (string) Uuid::uuid4()->toString(),
                                    'daily_report_uuid' => $draft->uuid,
                                    'statusenabled' => true,
                                    'checked' => json_encode($checked),
                                    'keterangan' => json_encode($keterangan),
                                    'siang' => json_encode($morning),
                                    'malam' => json_encode($night),
                                ]
                            );
                        }
                    }
                }


//                if (!empty($request->front_loading)) {
//                    foreach ($request->front_loading as $front_unit) {
//                        $timeData = $front_unit["time"] ?? [];
//
//                        $morning = [];
//                        $night = [];
//                        $checked = [];
//                        $keterangan = [];
//
//                        foreach ($timeData as $time) {
//                            // Validasi apakah kunci 'value' ada
//                            if (!isset($time['value'])) {
//                                continue; // Jika tidak ada, lewati iterasi ini
//                            }
//
//                            if ($time['checked'] == true || !empty($time['keterangan'])) {
//                                $timeSlots = explode('|', $time['value']);
//
//                                if (isset($timeSlots[0])) {
//                                    $morning[] = trim($timeSlots[0]);
//                                }
//                                if (isset($timeSlots[1])) {
//                                    $night[] = trim($timeSlots[1]);
//                                }
//
//                                $checked[] = $time['checked'] == "false" ? null : $time['checked'];
//                                $keterangan[] = $time['keterangan'] ?? null;
//                            }
//                        }
//
//                        if (!empty($checked)) {
//                            FrontLoading::updateOrCreate(
//                                [
//                                    'daily_report_id' => $draft->id,
//                                    'nomor_unit' => $front_unit["nomor_unit"],
//                                    'is_draft' => true,
//                                ],
//                                [
//                                    'uuid' => $front_unit["uuid"] ?? (string) Uuid::uuid4()->toString(),
//                                    'daily_report_uuid' => $draft->uuid,
//                                    'statusenabled' => true,
//                                    'checked' => json_encode($checked),
//                                    'keterangan' => json_encode($keterangan),
//                                    'siang' => json_encode($morning),
//                                    'malam' => json_encode($night),
//                                ]
//                            );
//                        }
//                    }
//                }



                // Insert alat support
                if (!empty($request->alat_support)) {
                    foreach ($request->alat_support as $value) {
                        $operator = explode('|', $value['namaSupport']);
                        $nikOperator = $operator[0];
                        $namaOperator = trim($operator[1]);
                        $jenisUnit = substr($value['unitSupport'], 0, 2);

                        AlatSupport::updateOrCreate(
                            [
                                'daily_report_id' => $draft->id,
                                'alat_unit' => $value['unitSupport'],
                                'is_draft' => true,
                            ],
                            [
                                'uuid' => (string) Uuid::uuid4()->toString(),
                                'daily_report_uuid' => $draft->uuid,
                                'statusenabled' => true,
                                'jenis_unit' => $jenisUnit,
                                'nik_operator' => $nikOperator,
                                'nama_operator' => $namaOperator,
                                'tanggal_operator' => \Carbon\Carbon::createFromFormat('m/d/Y', $value['tanggalSupport'])->format('Y-m-d'),
                                'shift_operator_id' => $value['shiftSupport'],
                                'hm_awal' => $value['hmAwalSupport'],
                                'hm_akhir' => $value['hmAkhirSupport'],
                                'hm_total' => $value['hmAkhirSupport'] - $value['hmAwalSupport'],
                                'hm_cash' => $value['hmCashSupport'],
                                'keterangan' => $value['keteranganSupport'],
                            ]
                        );
                    }
                }

                // Insert catatan pengawas
                if (!empty($request->catatan)) {
                    foreach ($request->catatan as $catatan) {
                        CatatanPengawas::updateOrCreate(
                            [
                                'daily_report_id' => $draft->id,
                                'jam_start' => $catatan['start_catatan'],
                                'jam_stop' => $catatan['end_catatan'],
                                'is_draft' => true,
                            ],
                            [
                                'uuid' => (string) Uuid::uuid4()->toString(),
                                'daily_report_uuid' => $draft->uuid,
                                'statusenabled' => true,
                                'keterangan' => $catatan['description_catatan'],
                            ]
                        );
                    }
                }

//                dd($draft);

                return response()->json([
                    'message' => 'Draft berhasil disimpan',
                    'draft' => $draft,
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan draft',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifiedAll($uuid)
    {
        $klkh =  DailyReport::where('uuid', $uuid)->first();

        try {
            DailyReport::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'KLKH Loading Point berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Loading Point gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman($uuid)
    {
        $klkh =  DailyReport::where('uuid', $uuid)->first();

        try {
            DailyReport::where('id', $klkh->id)->update([
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
        $klkh =  DailyReport::where('uuid', $uuid)->first();

        try {
            DailyReport::where('id', $klkh->id)->update([
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
        $klkh =  DailyReport::where('uuid', $uuid)->first();

        try {
            DailyReport::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Form/laporan berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

}
