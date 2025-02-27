<?php

namespace App\Http\Controllers;

use App\Models\AlatSupport;
use App\Models\Area;
use App\Models\CatatanPengawas;
use App\Models\DailyReport;
use App\Models\FrontLoading;
use App\Models\Log;
use App\Models\Lokasi;
use App\Models\Material;
use App\Models\Personal;
use App\Models\Shift;
use App\Models\Unit;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DateTimeImmutable;

class FormPengawasNewController extends Controller
{
    //
    public function index()
    {

        $today = now()->toDateString(); // Mendapatkan tanggal hari ini

        $daily = DailyReport::where('foreman_id', Auth::user()->id)
            ->where(function ($query) use ($today) {
                $query->where('is_draft', true)
                    ->orWhere(function ($q) use ($today) {
                        $q->where('is_draft', false)
                          ->whereDate('created_at', '!=', $today);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->first();





        // Jika laporan hari ini sudah final (is_draft = false), kosongkan $daily
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



         // Array waktu siang dan malam
    $staticTimeSlots = [
        ['siang' => '07.00 - 08.00', 'malam' => '19.00 - 20.00'],
        ['siang' => '08.00 - 09.00', 'malam' => '20.00 - 21.00'],
        ['siang' => '09.00 - 10.00', 'malam' => '21.00 - 22.00'],
        ['siang' => '10.00 - 11.00', 'malam' => '22.00 - 23.00'],
        ['siang' => '11.00 - 12.00', 'malam' => '23.00 - 24.00'],
        ['siang' => '12.00 - 13.00', 'malam' => '24.00 - 01.00'],
        ['siang' => '13.00 - 14.00', 'malam' => '01.00 - 02.00'],
        ['siang' => '14.00 - 15.00', 'malam' => '02.00 - 03.00'],
        ['siang' => '15.00 - 16.00', 'malam' => '03.00 - 04.00'],
        ['siang' => '16.00 - 17.00', 'malam' => '04.00 - 05.00'],
        ['siang' => '17.00 - 18.00', 'malam' => '05.00 - 06.00'],
        ['siang' => '18.00 - 19.00', 'malam' => '06.00 - 07.00'],
    ];



        $front_loading = [];
        $alatSupports = [];
        $supervisorNotes = [];

        if ($daily) {
            $front_loading = FrontLoading::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();
            //dd($front_loading);
            foreach ($front_loading as $loading) {
                $loading->front_loading_uuid = $loading->uuid;
                $loading->siang = json_decode($loading->siang, true) ?? [];
                $loading->malam = json_decode($loading->malam, true) ?? [];
                $loading->checked = json_decode($loading->checked, true) ?? [];
                $loading->keterangan = json_decode($loading->keterangan, true) ?? [];
            }


            //dd($frontLoading->toArray());
            $alatSupports = AlatSupport::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();

            $supervisorNotes = CatatanPengawas::where('daily_report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();
        }

        // if(empty($daily)){
        //     return view('form-pengawas.empty');
        // }
        $ex = Unit::select([
            'VHC_ID',
            'VHC_TYPEID',
            'VHC_GROUPID',
            'VHC_ACTIVE',
        ])
            ->where('VHC_TYPEID', 1)
            // ->where('VHC_ID', 'like', 'EX%')
            // ->where('VHC_ACTIVE', true)
            ->get();

        $nomor_unit = Unit::select('VHC_ID')
            ->where('VHC_ID', 'NOT LIKE', 'HD%')
            ->get();

        $operator = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
        )->where('ROLETYPE', 0)->get();

        $supervisor = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
        )->where('ROLETYPE', 3)->get();

        $superintendent = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY',
            DB::raw("CASE WHEN ROLETYPE = 3 THEN 'SUPERVISOR' WHEN ROLETYPE = 4 THEN 'SUPERINTENDENT' ELSE 'UNKNOWN' END as JABATAN ")
        )->whereIn('ROLETYPE', [3, 4])->get();

        $lokasi = Lokasi::where('statusenabled', true)->get();
        $area = Area::where('statusenabled', true)->get();
        $shift = Shift::where('statusenabled', true)->get();


        $data = [
            'operator' => $operator,
            'supervisor' => $supervisor,
            'superintendent' => $superintendent,
            'EX' => $ex,
            'nomor_unit' => $nomor_unit,
            'lokasi' => $lokasi,
            'area' => $area,
            'shift' => $shift,
        ];

    //    dd($daily);


        return view('form-pengawas-new.index', compact('data', 'daily', 'front_loading', 'staticTimeSlots', 'alatSupports', 'supervisorNotes'));
    }

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
        $lokasi = Lokasi::where('id', $request->lokasi)->first();
        $area = Area::where('id', $request->area)->first();
        $shift = Shift::where('id', $request->shift_dasar)->first();

        Log::create([
            'tanggal_loging' => now(),
            'jenis_loging' => 'Laporan Kerja',
            'nama_user' => Auth::user()->id,
            'nik' => Auth::user()->nik,
            'keterangan' => 'Tambah laporan kerja dengan nama: '. Auth::user()->name . ', NIK: '. Auth::user()->nik . ', Role: '. Auth::user()->role .
            ', shift: '. $shift->keterangan . ', area: '. $area->keterangan . ', lokasi: '. $lokasi->keterangan,
        ]);
        // dd($request->all());
        try {
            return DB::transaction(function () use ($request) {

                    DailyReport::where('uuid', $request->uuid)->update(['is_draft' => false]);
                    FrontLoading::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);
                    AlatSupport::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);
                    CatatanPengawas::where('daily_report_uuid', $request->uuid)->update(['is_draft' => false]);


            // return response()->json([
            //     'success' => true,
            //     'message' => 'Laporan berhasil dibuat',
            //     'redirect' => route('form-pengawas-new.index'), // URL redirect
            // ]);

            // Respons untuk submit form biasa
            return redirect()->route('form-pengawas-new.show')->with('success', 'Laporan berhasil dibuat');
            });
        } catch (\Throwable $th) {
            // return response()->json([
            //     'success' => false,
            //  //   'message' => 'Laporan gagal dibuat',
            //  'message' => $th->getMessage(),
            //  'error' => $th->getMessage(),
            // ], 500);
           return redirect()->route('form-pengawas-new.index')->with('info', 'Laporan gagal dibuat.. \n' . $th->getMessage());
        }
    }

    public function saveAsDraft(Request $request)
    {

        try {
            return DB::transaction(function () use ($request) {
                $uuid = $request->uuid;

            // Jika UUID tidak kosong, cek apakah draft sudah ada
            $dailyReport = !empty($uuid)
                ? DailyReport::where('uuid', $uuid)->first()
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
                'area_id' => $request->area,
                'lokasi_id' => $request->lokasi,
                'nik_superintendent' => $nikSuperintendent,
                'nama_superintendent' => $namaSuperintendent,
                'nik_supervisor' => $nikSupervisor,
                'nama_supervisor' => $namaSupervisor,
                'is_draft' => true, // Default sebagai draft
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
            $dailyReport = DailyReport::updateOrCreate(
                ['uuid' => $uuid], // Kondisi pencarian
                $data
            );

            // insert front loading
            if (!empty($request->front_loading)) {
                $frontLoadingData = json_decode($request->front_loading, true);
            if (!is_array($frontLoadingData)) {
                return response()->json(['error' => 'Invalid front_loading data format'], 422);
            }

            foreach ($frontLoadingData as $unitData) {
                $nomorUnit = $unitData['nomor_unit'] ?? null;
                $morningSlots = $unitData['siang'] ?? [];
                $nightSlots = $unitData['malam'] ?? [];
                $checkedStatus = $unitData['checked'] ?? [];
                $descriptions = $unitData['keterangan'] ?? [];

                // Validasi panjang array
                $totalSlots = count($morningSlots);
                if ($totalSlots !== count($nightSlots) || $totalSlots !== count($checkedStatus) || $totalSlots !== count($descriptions)) {
                    return response()->json(['error' => 'Mismatched slot data lengths for unit ' . $nomorUnit], 422);
                }

                // Update atau buat data untuk setiap unit
                FrontLoading::updateOrCreate(
                    [
                        'daily_report_id' => $dailyReport->id,
                        'nomor_unit' => $nomorUnit,
                    ],
                    [
                        'uuid' => $unitData['uuid'] ?? Uuid::uuid4()->toString(),
                        'daily_report_id' => $dailyReport->id,
                        'daily_report_uuid' => $dailyReport->uuid,
                        'statusenabled' => true,
                        'nomor_unit' => $nomorUnit,
                        'siang' => json_encode($morningSlots),
                        'malam' => json_encode($nightSlots),
                        'checked' => json_encode($checkedStatus),
                        'keterangan' => json_encode($descriptions),
                        'is_draft' => true,
                    ]
                );
            }

            }


            if (!empty($request->alat_support)) {
                $alatSupports = json_decode($request->alat_support, true); // Decode JSON dari request alat_support

                foreach ($alatSupports as $value) {
                    // dd($value);

                    $nikOperator = $value['nik_operator'] ?? null;
                    $namaOperator = $value['nama_operator'] ?? null;

                    // Ambil Jenis Unit berdasarkan dua karakter pertama alat_unit
                    $jenisUnit = substr($value['alat_unit'], 0, 2);

                    // Hitung HM Total (Jika null, set ke 0 untuk menghindari error)
                    $hmAwal = isset($value['hm_awal']) ? (float) $value['hm_awal'] : 0;
                    $hmAkhir = isset($value['hm_akhir']) ? (float) $value['hm_akhir'] : 0;
                    $hmTotal = $hmAkhir - $hmAwal;

                    // Simpan atau Perbarui Data
                    AlatSupport::updateOrCreate(
                        [
                            'uuid' => $value['uuid'], // Kondisi untuk memastikan data unik
                            // 'alat_unit' => $value['alat_unit'], // Pastikan alat_unit unik dalam satu laporan
                        ],
                        [
                            // 'uuid' => $value['uuid'] ?? (string) Uuid::uuid4()->toString(),
                            'daily_report_uuid' => $dailyReport->uuid,
                            'daily_report_id' => $dailyReport->id,
                            'statusenabled' => true,
                            'jenis_unit' => $jenisUnit,
                            'alat_unit' => $value['alat_unit'],
                            'nik_operator' => $nikOperator,
                            'nama_operator' => $namaOperator,
                            'tanggal_operator' => isset($value['tanggal_operator']) ? \Carbon\Carbon::createFromFormat('Y-m-d', $value['tanggal_operator'])->format('Y-m-d') : null,
                            'shift_operator_id' => $value['shift_operator'] ?? null,
                            'hm_awal' => $hmAwal,
                            'hm_akhir' => $hmAkhir,
                            'hm_total' => $hmTotal,
                            'hm_cash' => $value['hm_cash'] ?? null,
                            'keterangan' => $value['keterangan'] ?? null,
                            'is_draft' => true,
                        ]
                    );

                    // AlatSupport::create(
                    //     [
                    //         'uuid' => (string) Uuid::uuid4()->toString(),
                    //         'daily_report_uuid' => $dailyReport->uuid,
                    //         'daily_report_id' => $dailyReport->id,
                    //         'statusenabled' => true,
                    //         'jenis_unit' => $jenisUnit,
                    //         'alat_unit' => $value['alat_unit'],
                    //         'nik_operator' => $nikOperator,
                    //         'nama_operator' => $namaOperator,
                    //         'tanggal_operator' => isset($value['tanggal_operator']) ? \Carbon\Carbon::createFromFormat('Y-m-d', $value['tanggal_operator'])->format('Y-m-d') : null,
                    //         'shift_operator_id' => $value['shift_operator'] ?? null,
                    //         'hm_awal' => $hmAwal,
                    //         'hm_akhir' => $hmAkhir,
                    //         'hm_total' => $hmTotal,
                    //         'hm_cash' => $value['hm_cash'] ?? null,
                    //         'keterangan' => $value['keterangan'] ?? null,
                    //         'is_draft' => true,
                    //     ]
                    // );
                }
            }


            if (!empty($request->catatan)) {
                foreach (json_decode($request->catatan, true) as $catatan) {
                    CatatanPengawas::updateOrCreate(
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
                            'is_draft' => true,
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

    // public function getDraft($uuid)
    // {
    //     try {

    //         // $DailyReportdraft = DailyReport::where('uuid', $uuid)->first();
    //         //ambil data yg latest dan yang tidak null uuid nya
    //         $DailyReportdraft = DailyReport::where('uuid', $uuid)->whereNotNull('uuid')->latest()->first();

    //         if (!$DailyReportdraft) {
    //             return response()->json(['error' => 'Draft not found'], 404);
    //         }




    //         return response()->json([
    //             'success' => true,
    //             'data' => $DailyReportdraft,
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => 'Failed to fetch draft: ' . $th->getMessage()], 500);
    //     }
    // }

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

        // dd($daily);

        return view('form-pengawas-new.daftar.index', compact('daily'));
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
        )->where('dr.statusenabled', true)
        ->where('dr.uuid', $uuid)->first();

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
        )->where('cp.statusenabled', true)
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
                    return $item === true or $item === 'true'; // Convert true string to boolean
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

        return view('form-pengawas-new.preview', compact(['data', 'timeSlots']));
    }

    public function bundlepdf()
    {

        if (empty(session('requestTimeLaporanKerja')['rangeStart']) || empty(session('requestTimeLaporanKerja')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeLaporanKerja')['rangeStart']);
            $end = new DateTime(session('requestTimeLaporanKerja')['rangeEnd']);
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
            'dr.uuid',
            'dr.foreman_id as pic',
            'us.name as nama_pic',
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
            'dr.verified_superintendent'
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

        // Fetch front loading data with joins
        $front = DB::table('front_loading_t as fl')
            ->leftJoin('daily_report_t as dr', 'fl.daily_report_id', '=', 'dr.id')
            ->leftJoin('focus.dbo.FLT_VEHICLE as flt', 'fl.nomor_unit', '=', 'flt.VHC_ID')
            ->select(
                'fl.nomor_unit',
                'fl.daily_report_uuid',
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
            ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $front = collect($front); // Ensure the data is a collection

        // Fetch support equipment data
        $support = DB::table('alat_support_t as al')
            ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
            ->leftJoin('REF_SHIFT as sh', 'al.shift_operator_id', '=', 'sh.id')
            ->select(
                'al.alat_unit as nomor_unit',
                'al.daily_report_uuid',
                'al.nama_operator',
                'al.hm_awal',
                'al.hm_akhir',
                'al.hm_cash',
                'al.keterangan',
                'sh.keterangan as shift',
                'al.tanggal_operator as tanggal'
            )
            ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $support = collect($support); // Ensure the data is a collection

        // Fetch catatan data
        $catatan = DB::table('catatan_pengawas_t as cp')
            ->leftJoin('daily_report_t as dr', 'cp.daily_report_id', '=', 'dr.id')
            ->select(
                'cp.jam_start',
                'cp.daily_report_uuid',
                'cp.jam_stop',
                'cp.keterangan'
            )
            ->whereIn('dr.uuid', $daily->pluck('uuid')->toArray())
            ->get();

        // Convert to collection
        $catatan = collect($catatan); // Ensure the data is a collection

        $timeSlots = [
            'siang' => ['07.00 - 08.00', '08.00 - 09.00', '09.00 - 10.00', '10.00 - 11.00', '11.00 - 12.00', '12.00 - 13.00', '13.00 - 14.00', '14.00 - 15.00', '15.00 - 16.00', '16.00 - 17.00', '17.00 - 18.00', '18.00 - 19.00'],
            'malam' => ['19.00 - 20.00', '20.00 - 21.00', '21.00 - 22.00', '22.00 - 23.00', '23.00 - 24.00', '24.00 - 01.00', '01.00 - 02.00', '02.00 - 03.00', '03.00 - 04.00', '04.00 - 05.00', '05.00 - 06.00', '06.00 - 07.00'],
        ];

        // Combine all data into one collection
        $combinedData = $daily->map(function ($dailyItem, $dailySupport) use ($front, $support, $catatan, $timeSlots) {
            // Relate front loading data to daily report using 'uuid'
            $frontData = $front->filter(function ($item) use ($dailyItem) {
                return $item->daily_report_uuid == $dailyItem->uuid; // Assuming 'uuid' is the relation key
            });

            $processedFrontData = $frontData->map(function ($unit) use ($timeSlots) {
                $siangTimes = json_decode($unit->siang, true);
                $malamTimes = json_decode($unit->malam, true);
                $checked = array_map(function ($item) {
                    return $item === 'true' or $item === true; // Convert true string to boolean
                }, json_decode($unit->checked, true));

                $keterangan = array_map(function ($item) {
                    return $item === null ? '' : $item; // Mengganti null dengan string kosong
                }, json_decode($unit->keterangan, true));

                // Proses waktu siang
                $siangResult = collect($timeSlots['siang'])->map(function ($slot) use ($siangTimes, $checked, $keterangan) {
                    $index = array_search($slot, $siangTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'slot' =>$slot,
                            'status' => '<img src="' . public_path('check.png') . '">', // Checkmark
                            'keterangan' => $keterangan[$index] ?? '', // Get corresponding keterangan
                        ];
                    }
                    return (object)[
                        'slot' =>$slot,
                        'status' => '',
                        'keterangan' => '', // No keterangan
                    ];
                });

                // Proses waktu malam
                $malamResult = collect($timeSlots['malam'])->map(function ($slot) use ($malamTimes, $checked, $keterangan) {
                    $index = array_search($slot, $malamTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'status' => '<img src="' . public_path('check.png') . '">', // Checkmark
                            'keterangan' => $keterangan[$index] ?? '', // Get corresponding keterangan
                        ];
                    }
                    return (object)[
                        'status' => '',
                        'keterangan' => '', // No keterangan
                    ];
                });

                // Kembalikan hasil
                return [
                    'brand' => $unit->brand,
                    'type' => $unit->type,
                    'nomor_unit' => $unit->nomor_unit,
                    'siang' => $siangResult,
                    'malam' => $malamResult,
                ];
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
                'frontLoading' => $processedFrontData,
                'supportEquipment' => $supportData,
                'catatan' => $catatanData,
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



        $pdf = PDF::loadView('form-pengawas-new.bundlepdf', compact('combinedData'));
        return $pdf->stream('Laporan Kerja.pdf');

        // return view('form-pengawas-old.bundlepdf', compact('combinedData'));
    }

    public function pdf($uuid)
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
            'us.name as nama_pic',
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
            $daily->verified_foreman = $daily->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_foreman)) : null;
            $daily->verified_supervisor = $daily->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_supervisor)) : null;
            $daily->verified_superintendent = $daily->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $daily->nama_superintendent)) : null;

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
            'malam' => ['19.00 - 20.00', '20.00 - 21.00', '21.00 - 22.00', '22.00 - 23.00', '23.00 - 24.00', '24.00 - 01.00', '01.00 - 02.00', '02.00 - 03.00', '03.00 - 04.00', '04.00 - 05.00', '05.00 - 06.00', '06.00 - 07.00'],
        ];

        // Menghasilkan data seperti '✓' untuk menandakan waktu yang dicentang
        $processedData = $front->map(function ($units, $brand) use ($timeSlots) {
            return $units->map(function ($unit) use ($timeSlots) {
                $siangTimes = json_decode($unit->siang, true);
                $malamTimes = json_decode($unit->malam, true);
                $checked = array_map(function ($item) {
                    return $item === true or $item === 'true'; // Convert true string to boolean
                }, json_decode($unit->checked, true));
                $keterangan = array_map(function ($item) {
                    return $item === null ? '' : $item; // Mengganti null dengan string kosong
                }, json_decode($unit->keterangan, true));

                $siangResult = collect($timeSlots['siang'])->map(function ($slot) use ($siangTimes, $checked, $keterangan) {
                    $index = array_search($slot, $siangTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'status' => '<img src="' . public_path('check.png') . '">', // Checkmark
                            'keterangan' => $keterangan[$index] ?? '', // Get corresponding keterangan
                        ];
                    }
                    return (object)[
                        'status' => '',
                        'keterangan' => '', // No keterangan
                    ];
                });
                $malamResult = collect($timeSlots['malam'])->map(function ($slot) use ($malamTimes, $checked, $keterangan) {
                    $index = array_search($slot, $malamTimes);
                    if ($index !== false && $checked[$index] === true) {
                        return (object)[
                            'status' => '<img src="' . public_path('check.png') . '">', // Checkmark
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
                    'malam' => $malamResult,
                ];
            });
        });

        $data = [
            'daily' => $daily,
            'front' => $processedData,
            'support' => $support,
            'catatan' => $catatan,
        ];

        $pdf = PDF::loadView('form-pengawas-new.pdf', compact(['data', 'timeSlots']));
        return $pdf->stream('Laporan Kerja Produksi-'. $data['daily']->tanggal .'-'. $data['daily']->shift .'-'. $data['daily']->nama_pic .'.pdf');

        // return view('form-pengawas-old.pdf', compact(['data', 'timeSlots']));
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
                    return $item === true or $item === 'true'; // Convert true string to boolean
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

        return view('form-pengawas-new.download', compact(['data', 'timeSlots']));
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

            return redirect()->back()->with('success', 'Form/laporan berhasil berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Form/laporan berhasil gagal diverifikasi..\n' . $th->getMessage()));
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

    public function delete($uuid)
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
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
            'us.name as pic',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',

        )->where('dr.uuid', $uuid)->first();

        try {

            Log::create([
                'tanggal_loging' => now(),
                'jenis_loging' => 'Laporan Kerja',
                'nama_user' => Auth::user()->id,
                'nik' => Auth::user()->nik,
                'keterangan' => 'Hapus laporan kerja dengan PIC: '. $daily->pic . ', tanggal pembuatan: '. $daily->tanggal .
                ', shift: '. $daily->shift . ', area: '. $daily->area . ', lokasi: '. $daily->lokasi,
            ]);

            DailyReport::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            FrontLoading::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            AlatSupport::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            CatatanPengawas::where('daily_report_uuid', $uuid)->update([
                'statusenabled' => false,
                'is_draft' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Laporan kerja berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'Laporan kerja gagal dihapus');
        }
    }
}
