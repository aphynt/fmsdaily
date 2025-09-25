<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use App\Models\Personal;
use App\Models\Shift;
use App\Models\Area;
use App\Models\KLKHOGS;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class KLKHOGSController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeOGS' => $request->all()]);

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


        $baseQuery = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'ogs.foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'ogs.supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'ogs.superintendent', '=', 'us5.nik')
        ->select(
            'ogs.id',
            'ogs.uuid',
            'ogs.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, ogs.created_at, 120) as tanggal_pembuatan'),
            'ogs.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'ogs.foreman as nik_foreman',
            'us3.name as nama_foreman',
            'ogs.supervisor as nik_supervisor',
            'us4.name as nama_supervisor',
            'ogs.superintendent as nik_superintendent',
            'us5.name as nama_superintendent',
            'ogs.verified_foreman',
            'ogs.verified_supervisor',
            'ogs.verified_superintendent',
            'ogs.date',
            'ogs.time',
        )
        ->where('ogs.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, ogs.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

        // if (Auth::user()->role == 'FOREMAN') {
        //     $baseQuery->where('foreman', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERVISOR') {
        //     $baseQuery->where('supervisor', Auth::user()->nik);
        // }
        // if (Auth::user()->role == 'SUPERINTENDENT') {
        //     $baseQuery->where('superintendent', Auth::user()->nik);
        // }
        if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY'])) {
            $baseQuery->orWhere('pic', Auth::user()->id);
        }

        $baseQuery = $baseQuery->where(function($query) {
            $query->where('ogs.foreman', Auth::user()->nik)
                  ->orWhere('ogs.supervisor', Auth::user()->nik)
                  ->orWhere('ogs.superintendent', Auth::user()->nik);
        });

        $ogs = $baseQuery->get();

        return view('klkh.ogs.index', compact('ogs'));
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
        return view('klkh.ogs.insert', compact('users'));
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
                    'rata_padat_check' => $data['rata_padat_check'] ?? null,
                    'rata_padat_note' => $data['rata_padat_note'] ?? null,
                    'parkir_terpisah_check' => $data['parkir_terpisah_check'] ?? null,
                    'parkir_terpisah_note' => $data['parkir_terpisah_note'] ?? null,
                    'ceceran_oli_check' => $data['ceceran_oli_check'] ?? null,
                    'ceceran_oli_note' => $data['ceceran_oli_note'] ?? null,
                    'genangan_air_check' => $data['genangan_air_check'] ?? null,
                    'genangan_air_note' => $data['genangan_air_note'] ?? null,
                    'rambu_darurat_check' => $data['rambu_darurat_check'] ?? null,
                    'rambu_darurat_note' => $data['rambu_darurat_note'] ?? null,
                    'rambu_lalulintas_check' => $data['rambu_lalulintas_check'] ?? null,
                    'rambu_lalulintas_note' => $data['rambu_lalulintas_note'] ?? null,
                    'rambu_berhenti_check' => $data['rambu_berhenti_check'] ?? null,
                    'rambu_berhenti_note' => $data['rambu_berhenti_note'] ?? null,
                    'rambu_masuk_keluar_check' => $data['rambu_masuk_keluar_check'] ?? null,
                    'rambu_masuk_keluar_note' => $data['rambu_masuk_keluar_note'] ?? null,
                    'rambu_ogs_check' => $data['rambu_ogs_check'] ?? null,
                    'rambu_ogs_note' => $data['rambu_ogs_note'] ?? null,
                    'papan_nama_check' => $data['papan_nama_check'] ?? null,
                    'papan_nama_note' => $data['papan_nama_note'] ?? null,
                    'emergency_call_check' => $data['emergency_call_check'] ?? null,
                    'emergency_call_note' => $data['emergency_call_note'] ?? null,
                    'tempat_sampah_check' => $data['tempat_sampah_check'] ?? null,
                    'tempat_sampah_note' => $data['tempat_sampah_note'] ?? null,
                    'penyalur_petir_check' => $data['penyalur_petir_check'] ?? null,
                    'penyalur_petir_note' => $data['penyalur_petir_note'] ?? null,
                    'tempat_istirahat_check' => $data['tempat_istirahat_check'] ?? null,
                    'tempat_istirahat_note' => $data['tempat_istirahat_note'] ?? null,
                    'apar_check' => $data['apar_check'] ?? null,
                    'apar_note' => $data['apar_note'] ?? null,
                    'kotak_p3k_check' => $data['kotak_p3k_check'] ?? null,
                    'kotak_p3k_note' => $data['kotak_p3k_note'] ?? null,
                    'penerangan_check' => $data['penerangan_check'] ?? null,
                    'penerangan_note' => $data['penerangan_note'] ?? null,
                    'kamar_mandi_check' => $data['kamar_mandi_check'] ?? null,
                    'kamar_mandi_note' => $data['kamar_mandi_note'] ?? null,
                    'permukaan_tanah_check' => $data['permukaan_tanah_check'] ?? null,
                    'permukaan_tanah_note' => $data['permukaan_tanah_note'] ?? null,
                    'akses_jalan_check' => $data['akses_jalan_check'] ?? null,
                    'akses_jalan_note' => $data['akses_jalan_note'] ?? null,
                    'tinggi_tanggul_check' => $data['tinggi_tanggul_check'] ?? null,
                    'tinggi_tanggul_note' => $data['tinggi_tanggul_note'] ?? null,
                    'lebar_bus_check' => $data['lebar_bus_check'] ?? null,
                    'lebar_bus_note' => $data['lebar_bus_note'] ?? null,
                    'lebar_hd_check' => $data['lebar_hd_check'] ?? null,
                    'lebar_hd_note' => $data['lebar_hd_note'] ?? null,
                    'jalur_hd_check' => $data['jalur_hd_check'] ?? null,
                    'jalur_hd_note' => $data['jalur_hd_note'] ?? null,
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

            KLKHOGS::create($dataToInsert);

            return redirect()->route('klkh.ogs')->with('success', 'KLKH OGS berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.ogs')->with('info', nl2br('KLKH OGS gagal dibuat..\n' . $th->getMessage()));
        }
    }

    public function cetak($uuid)
    {
        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'ogs.foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'ogs.supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'ogs.superintendent', '=', 'us5.nik')
        ->select(
            'ogs.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('ogs.statusenabled', true)
        ->where('ogs.uuid', $uuid)->first();

        if($ogs == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $ogs;

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

        return view('klkh.ogs.cetak', compact('ogs'));
    }

    public function download($uuid)
    {
        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'ogs.foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'ogs.supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'ogs.superintendent', '=', 'us5.nik')
        ->select(
            'ogs.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('ogs.statusenabled', true)
        ->where('ogs.uuid', $uuid)->first();

        if($ogs == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $ogs;

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

        $pdf = PDF::loadView('klkh.ogs.download', compact('ogs'));
        return $pdf->download('KLKH OGS-'. $ogs->date .'-'. $ogs->shift .'-'. $ogs->nama_pic .'.pdf');

    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeOGS')['rangeStart']) || empty(session('requestTimeOGS')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeOGS')['rangeStart']);
            $end = new DateTime(session('requestTimeOGS')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'ogs.foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'ogs.supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'ogs.superintendent', '=', 'us5.nik')
        ->select(
            'ogs.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('ogs.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, ogs.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($ogs->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $ogs = $ogs->map(function($item) {
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

        $pdf = PDF::loadView('klkh.ogs.bundlepdf', compact('ogs'));
        return $pdf->download('KLKH OGS.pdf');

    }

    public function preview($uuid)
    {
        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'ogs.foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'ogs.supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'ogs.superintendent', '=', 'us5.nik')
        ->select(
            'ogs.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('ogs.statusenabled', true)
        ->where('ogs.uuid', $uuid)->first();

        if($ogs == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $ogs;

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

        return view('klkh.ogs.preview', compact('ogs'));
    }

    public function delete($id)
    {
        try {
            KLKHOGS::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.ogs')->with('success', 'KLKH OGS berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.ogs')->with('info', nl2br('KLKH OGS gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHOGS::where('uuid', $uuid)->first();

        try {
            KLKHOGS::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH OGS berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH OGS gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHOGS::where('uuid', $uuid)->first();

        try {
            KLKHOGS::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH OGS berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH OGS gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHOGS::where('uuid', $uuid)->first();

        try {
            KLKHOGS::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH OGS berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH OGS gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHOGS::where('uuid', $uuid)->first();

        try {
            KLKHOGS::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH OGS berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH OGS gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
