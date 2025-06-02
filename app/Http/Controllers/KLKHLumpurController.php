<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\KLKHLumpur;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use App\Models\Personal;
use App\Models\Shift;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class KLKHLumpurController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeLumpur' => $request->all()]);

        if (empty($request->rangeStart) || empty($request->rangeEnd)){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime("$request->rangeStart");
            $end = new DateTime("$request->rangeEnd");
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $baseQuery = DB::table('klkh_lumpur_t as lum')
        ->leftJoin('users as us', 'lum.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lum.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lum.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lum.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lum.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lum.superintendent', '=', 'spt.NRP')
        ->select(
            'lum.id',
            'lum.uuid',
            'lum.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, lum.created_at, 120) as tanggal_pembuatan'),
            'lum.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'lum.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'lum.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'lum.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lum.verified_foreman',
            'lum.verified_supervisor',
            'lum.verified_superintendent',
            'lum.date',
            'lum.time',
        )
        ->where('lum.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lum.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

        // if (Auth::user()->role == 'FOREMAN') {
        //     $baseQuery->where('foreman', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERVISOR') {
        //     $baseQuery->where('supervisor', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERINTENDENT') {
        //     $baseQuery->where('superintendent', Auth::user()->nik);
        // }
        if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
            $baseQuery->orWhere('pic', Auth::user()->id);
        }

        $baseQuery = $baseQuery->where(function($query) {
            $query->where('lum.foreman', Auth::user()->nik)
                  ->orWhere('lum.supervisor', Auth::user()->nik)
                  ->orWhere('lum.superintendent', Auth::user()->nik);
        });

        $lumpur = $baseQuery->get();

        return view('klkh.lumpur.index', compact('lumpur'));
    }

    public function insert()
    {
        $supervisor = Personal::where('ROLETYPE', 3)->get();
        $superintendent = Personal::whereIn('ROLETYPE', [3, 4])
        ->select('*', DB::raw("CASE WHEN ROLETYPE = 3 THEN 'SUPERVISOR' WHEN ROLETYPE = 4 THEN 'SUPERINTENDENT' ELSE 'UNKNOWN' END as JABATAN "))
        ->orderBy(DB::raw("CASE WHEN ROLETYPE = 3 THEN 1 WHEN ROLETYPE = 4 THEN 2 ELSE 3 END "))->get();
        $pit = Area::where('statusenabled', true)->get();
        $shift = Shift::where('statusenabled', true)->get();

        $users = [
            'supervisor' => $supervisor,
            'superintendent' => $superintendent,
            'pit' => $pit,
            'shift' => $shift,
        ];
        return view('klkh.lumpur.insert', compact('users'));
    }

    public function post(Request $request)
    {
        // dd($request->all());

        try {

            $data = $request->all();
            $dataToInsert = [
                    'pic' => Auth::user()->id,
                    'uuid' => (string) Uuid::uuid4()->toString(),
                    'statusenabled' => true,
                    'pit_id' => $data['pit'],
                    'shift_id' => $data['shift'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'unit_breakdown_check' => $data['unit_breakdown_check'] ?? null,
                    'unit_breakdown_note' => $data['unit_breakdown_note'] ?? null,
                    'rambu_check' => $data['rambu_check'] ?? null,
                    'rambu_note' => $data['rambu_note'] ?? null,
                    'grade_check' => $data['grade_check'] ?? null,
                    'grade_note' => $data['grade_note'] ?? null,
                    'unit_maintenance_check' => $data['unit_maintenance_check'] ?? null,
                    'unit_maintenance_note' => $data['unit_maintenance_note'] ?? null,
                    'debu_check' => $data['debu_check'] ?? null,
                    'debu_note' => $data['debu_note'] ?? null,
                    'lebar_jalan_check' => $data['lebar_jalan_check'] ?? null,
                    'lebar_jalan_note' => $data['lebar_jalan_note'] ?? null,
                    'blind_spot_check' => $data['blind_spot_check'] ?? null,
                    'blind_spot_note' => $data['blind_spot_note'] ?? null,
                    'kondisi_jalan_check' => $data['kondisi_jalan_check'] ?? null,
                    'kondisi_jalan_note' => $data['kondisi_jalan_note'] ?? null,
                    'tanggul_jalan_check' => $data['tanggul_jalan_check'] ?? null,
                    'tanggul_jalan_note' => $data['tanggul_jalan_note'] ?? null,
                    'pengelolaan_air_check' => $data['pengelolaan_air_check'] ?? null,
                    'pengelolaan_air_note' => $data['pengelolaan_air_note'] ?? null,
                    'crack_check' => $data['crack_check'] ?? null,
                    'crack_note' => $data['crack_note'] ?? null,
                    'luas_area_check' => $data['luas_area_check'] ?? null,
                    'luas_area_note' => $data['luas_area_note'] ?? null,
                    'tanggul_check' => $data['tanggul_check'] ?? null,
                    'tanggul_note' => $data['tanggul_note'] ?? null,
                    'free_dump_check' => $data['free_dump_check'] ?? null,
                    'free_dump_note' => $data['free_dump_note'] ?? null,
                    'alokasi_material_check' => $data['alokasi_material_check'] ?? null,
                    'alokasi_material_note' => $data['alokasi_material_note'] ?? null,
                    'beda_level_check' => $data['beda_level_check'] ?? null,
                    'beda_level_note' => $data['beda_level_note'] ?? null,
                    'tinggi_dumpingan_check' => $data['tinggi_dumpingan_check'] ?? null,
                    'tinggi_dumpingan_note' => $data['tinggi_dumpingan_note'] ?? null,
                    'genangan_air_check' => $data['genangan_air_check'] ?? null,
                    'genangan_air_note' => $data['genangan_air_note'] ?? null,
                    'dumpingan_bergelombang_check' => $data['dumpingan_bergelombang_check'] ?? null,
                    'dumpingan_bergelombang_note' => $data['dumpingan_bergelombang_note'] ?? null,
                    'bendera_acuan_check' => $data['bendera_acuan_check'] ?? null,
                    'bendera_acuan_note' => $data['bendera_acuan_note'] ?? null,
                    'rambu_jarak_check' => $data['rambu_jarak_check'] ?? null,
                    'rambu_jarak_note' => $data['rambu_jarak_note'] ?? null,
                    'tower_lamp_check' => $data['tower_lamp_check'] ?? null,
                    'tower_lamp_note' => $data['tower_lamp_note'] ?? null,
                    'penyalur_petir_check' => $data['penyalur_petir_check'] ?? null,
                    'penyalur_petir_note' => $data['penyalur_petir_note'] ?? null,
                    'muster_point_check' => $data['muster_point_check'] ?? null,
                    'muster_point_note' => $data['muster_point_note'] ?? null,
                    'safety_bundwall_check' => $data['safety_bundwall_check'] ?? null,
                    'safety_bundwall_note' => $data['safety_bundwall_note'] ?? null,
                    'ring_buoy_check' => $data['ring_buoy_check'] ?? null,
                    'ring_buoy_note' => $data['ring_buoy_note'] ?? null,
                    'sling_ware_check' => $data['sling_ware_check'] ?? null,
                    'sling_ware_note' => $data['sling_ware_note'] ?? null,
                    'pondok_pengawas_check' => $data['pondok_pengawas_check'] ?? null,
                    'pondok_pengawas_note' => $data['pondok_pengawas_note'] ?? null,
                    'struktur_pengawas_check' => $data['struktur_pengawas_check'] ?? null,
                    'struktur_pengawas_note' => $data['struktur_pengawas_note'] ?? null,
                    'life_jacket_bulldozer_check' => $data['life_jacket_bulldozer_check'] ?? null,
                    'life_jacket_bulldozer_note' => $data['life_jacket_bulldozer_note'] ?? null,
                    'emergency_number_check' => $data['emergency_number_check'] ?? null,
                    'emergency_number_note' => $data['emergency_number_note'] ?? null,
                    'life_jacket_spotter_check' => $data['life_jacket_spotter_check'] ?? null,
                    'life_jacket_spotter_note' => $data['life_jacket_spotter_note'] ?? null,
                    'additional_notes' => $data['additional_notes'] ?? null,
                    'superintendent' => $data['superintendent'] ?? null,
            ];
            if (Auth::user()->role == 'SUPERVISOR') {
                $dataToInsert['supervisor'] = Auth::user()->nik;
                $dataToInsert['verified_supervisor'] = Auth::user()->nik;
            }

            if (Auth::user()->role == 'FOREMAN') {
                $dataToInsert['supervisor'] = $data['supervisor'] ?? null;
                $dataToInsert['foreman'] = Auth::user()->nik;
                $dataToInsert['verified_foreman'] = Auth::user()->nik;
            }

            KLKHLumpur::create($dataToInsert);

            return redirect()->route('klkh.lumpur')->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.lumpur')->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal dibuat..\n' . $th->getMessage()));
        }
    }

    public function cetak($uuid)
    {
        $lpr = DB::table('klkh_lumpur_t as lpr')
        ->leftJoin('users as us', 'lpr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lpr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lpr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lpr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lpr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lpr.superintendent', '=', 'spt.NRP')
        ->select(
            'lpr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('lpr.statusenabled', true)
        ->where('lpr.uuid', $uuid)->first();

        if($lpr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $lpr;

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_foreman != null) {
                $fileName = 'verified_foreman' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_foreman)]), $filePath);

                $item->verified_foreman = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_foreman = null;
            }

            if ($item->verified_supervisor != null) {
                $fileName = 'verified_supervisor' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_supervisor)]), $filePath);

                $item->verified_supervisor = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_supervisor = null;
            }

            if ($item->verified_superintendent != null) {
                $fileName = 'verified_superintendent' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_superintendent)]), $filePath);

                $item->verified_superintendent = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_superintendent = null;
            }
        }

        return view('klkh.lumpur.cetak', compact('lpr'));
    }

    public function download($uuid)
    {
        $lpr = DB::table('klkh_lumpur_t as lpr')
        ->leftJoin('users as us', 'lpr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lpr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lpr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lpr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lpr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lpr.superintendent', '=', 'spt.NRP')
        ->select(
            'lpr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('lpr.statusenabled', true)
        ->where('lpr.uuid', $uuid)->first();

        if($lpr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $lpr;

            $qrTempFolder = storage_path('app/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if($item->verified_foreman != null){
                $fileName = 'verified_foreman' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_foreman)]), $filePath);
                $item->verified_foreman = $filePath;
            }else{
                $item->verified_foreman == null;
            }

            if($item->verified_supervisor != null){
                $fileName = 'verified_supervisor' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_supervisor)]), $filePath);
                $item->verified_supervisor = $filePath;
            }else{
                $item->verified_supervisor == null;
            }

            if($item->verified_superintendent != null){
                $fileName = 'verified_superintendent' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_superintendent)]), $filePath);
                $item->verified_superintendent = $filePath;
            }else{
                $item->verified_superintendent == null;
            }
        }

        $pdf = PDF::loadView('klkh.lumpur.download', compact('lpr'));
        return $pdf->download('KLKH Dumping Lumpur-'. $lpr->date .'-'. $lpr->shift .'-'. $lpr->nama_pic .'.pdf');

    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeLumpur')['rangeStart']) || empty(session('requestTimeLumpur')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeLumpur')['rangeStart']);
            $end = new DateTime(session('requestTimeLumpur')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $lpr = DB::table('klkh_lumpur_t as lpr')
        ->leftJoin('users as us', 'lpr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lpr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lpr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lpr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lpr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lpr.superintendent', '=', 'spt.NRP')
        ->select(
            'lpr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('lpr.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lpr.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($lpr->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $lpr = $lpr->map(function($item) {
                $qrTempFolder = public_path('qr-temp'); // ⬅️ Simpan langsung di public/qr-temp
                if (!File::exists($qrTempFolder)) {
                    File::makeDirectory($qrTempFolder, 0755, true);
                }

                // FOREMAN
                if (!empty($item->verified_foreman)) {
                    $fileName = 'verified_foreman' . $item->uuid . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    if (!File::exists($filePath)) {
                        QrCode::size(150)
                            ->format('png')
                            ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_foreman)]), $filePath);
                    }

                    // ⬅️ Gunakan asset() karena file di dalam public/
                    $item->verified_foreman = asset('qr-temp/' . $fileName);
                } else {
                    $item->verified_foreman = null;
                }

                // SUPERVISOR
                if (!empty($item->verified_supervisor)) {
                    $fileName = 'verified_supervisor' . $item->uuid . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    if (!File::exists($filePath)) {
                        QrCode::size(150)
                            ->format('png')
                            ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_supervisor)]), $filePath);
                    }

                    $item->verified_supervisor = asset('qr-temp/' . $fileName);
                } else {
                    $item->verified_supervisor = null;
                }

                // SUPERINTENDENT
                if (!empty($item->verified_superintendent)) {
                    $fileName = 'verified_superintendent' . $item->uuid . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    if (!File::exists($filePath)) {
                        QrCode::size(150)
                            ->format('png')
                            ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_superintendent)]), $filePath);
                    }

                    $item->verified_superintendent = asset('qr-temp/' . $fileName);
                } else {
                    $item->verified_superintendent = null;
                }

                return $item;
            });

        }

        $pdf = PDF::loadView('klkh.lumpur.bundlepdf', compact('lpr'));
        return $pdf->download('KLKH Dumping Lumpur.pdf');

    }

    public function preview($uuid)
    {
        $lpr = DB::table('klkh_lumpur_t as lpr')
        ->leftJoin('users as us', 'lpr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lpr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lpr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lpr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lpr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lpr.superintendent', '=', 'spt.NRP')
        ->select(
            'lpr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('lpr.statusenabled', true)
        ->where('lpr.uuid', $uuid)->first();

        if($lpr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $lpr;

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_foreman != null) {
                $fileName = 'verified_foreman' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_foreman)]), $filePath);

                $item->verified_foreman = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_foreman = null;
            }

            if ($item->verified_supervisor != null) {
                $fileName = 'verified_supervisor' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_supervisor)]), $filePath);

                $item->verified_supervisor = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_supervisor = null;
            }

            if ($item->verified_superintendent != null) {
                $fileName = 'verified_superintendent' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_superintendent)]), $filePath);

                $item->verified_superintendent = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_superintendent = null;
            }
        }

        return view('klkh.lumpur.preview', compact('lpr'));
    }

    public function delete($id)
    {
        try {
            KLKHLumpur::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.lumpur')->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.lumpur')->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHLumpur::where('uuid', $uuid)->first();

        try {
            KLKHLumpur::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHLumpur::where('uuid', $uuid)->first();

        try {
            KLKHLumpur::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHLumpur::where('uuid', $uuid)->first();

        try {
            KLKHLumpur::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHLumpur::where('uuid', $uuid)->first();

        try {
            KLKHLumpur::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH Dumping di Kolam Air/Lumpur berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Dumping di Kolam Air/Lumpur gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
