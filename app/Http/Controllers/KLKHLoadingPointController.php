<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\KLKHLoadingPoint;
use App\Models\Personal;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KLKHLoadingPointController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeLoadingPoint' => $request->all()]);

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


        $baseQuery = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'lp.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'lp.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'lp.nik_superintendent', '=', 'us5.nik')
        ->select(
            'lp.id',
            'lp.uuid',
            'lp.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, lp.created_at, 120) as tanggal_pembuatan'),
            'lp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'lp.foreman as nik_foreman',
            'us3.name as nama_foreman',
            'lp.supervisor as nik_supervisor',
            'us4.name as nama_supervisor',
            'lp.superintendent as nik_superintendent',
            'us5.name as nama_superintendent',
            'lp.verified_foreman',
            'lp.verified_supervisor',
            'lp.verified_superintendent',
            'lp.date',
            'lp.time',
        )
        ->where('lp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

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
            $query->where('lp.foreman', Auth::user()->nik)
                  ->orWhere('lp.supervisor', Auth::user()->nik)
                  ->orWhere('lp.superintendent', Auth::user()->nik);
        });

        $loading = $baseQuery->get();

        // $loading->each(function($item) {
        //     $item->verified_foreman = $item->verified_foreman == null ? 'Unverified' : 'Verified';
        //     $item->verified_supervisor = $item->verified_supervisor == null ? 'Unverified' : 'Verified';
        //     $item->verified_superintendent = $item->verified_superintendent == null ? 'Unverified' : 'Verified';
        // });

        return view('klkh.loading-point.index', compact('loading'));
    }

    public function preview($uuid)
    {
        $ld = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'lp.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'lp.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'lp.nik_superintendent', '=', 'us5.nik')
        ->select(
            'lp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('lp.statusenabled', true)
        ->where('lp.uuid', $uuid)->first();

        if($ld == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $ld;

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

        return view('klkh.loading-point.preview', compact('ld'));
    }

    public function cetak($uuid)
    {
        $lp = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'lp.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'lp.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'lp.nik_superintendent', '=', 'us5.nik')
        ->select(
            'lp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('lp.statusenabled', true)
        ->where('lp.uuid', $uuid)->first();

        if($lp == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $lp;

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

        return view('klkh.loading-point.cetak', compact('lp'));
    }

    public function download($uuid)
    {
        $lp = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'lp.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'lp.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'lp.nik_superintendent', '=', 'us5.nik')
        ->select(
            'lp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('lp.statusenabled', true)
        ->where('lp.uuid', $uuid)->first();

        if($lp == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $lp;

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

        $pdf = PDF::loadView('klkh.loading-point.download', compact('lp'));
        return $pdf->download('KLKH Loading Point-'. $lp->date .'-'. $lp->shift .'-'. $lp->nama_pic .'.pdf');

    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeLoadingPoint')['rangeStart']) || empty(session('requestTimeLoadingPoint')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeLoadingPoint')['rangeStart']);
            $end = new DateTime(session('requestTimeLoadingPoint')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $lp = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('users as us3', 'lp.nik_foreman', '=', 'us3.nik')
        ->leftJoin('users as us4', 'lp.nik_supervisor', '=', 'us4.nik')
        ->leftJoin('users as us5', 'lp.nik_superintendent', '=', 'us5.nik')
        ->select(
            'lp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'us3.name as nama_foreman',
            'us4.name as nama_supervisor',
            'us5.name as nama_superintendent'
            )
        ->where('lp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($lp->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $lp = $lp->map(function($item) {
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

        $pdf = PDF::loadView('klkh.loading-point.bundlepdf', compact('lp'));
        return $pdf->download('KLKH Loading Point.pdf');

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
        return view('klkh.loading-point.insert', compact('users'));
    }

    public function post(Request $request)
    {
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
                'loading_point_check' => $data['loading_point_check'],
                'loading_point_note' => $data['loading_point_note'] ?? null,
                'front_surface_check' => $data['front_surface_check'],
                'front_surface_note' => $data['front_surface_note'] ?? null,
                'bench_work_check' => $data['bench_work_check'],
                'bench_work_note' => $data['bench_work_note'] ?? null,
                'access_dike_check' => $data['access_dike_check'],
                'access_dike_note' => $data['access_dike_note'] ?? null,
                'loading_point_width_check' => $data['loading_point_width_check'],
                'loading_point_width_note' => $data['loading_point_width_note'] ?? null,
                'drainage_check' => $data['drainage_check'],
                'drainage_note' => $data['drainage_note'] ?? null,
                'no_waves_check' => $data['no_waves_check'],
                'no_waves_note' => $data['no_waves_note'] ?? null,
                'unit_placement_check' => $data['unit_placement_check'],
                'unit_placement_note' => $data['unit_placement_note'] ?? null,
                'material_stock_check' => $data['material_stock_check'],
                'material_stock_note' => $data['material_stock_note'] ?? null,
                'loading_hauling_check' => $data['loading_hauling_check'],
                'loading_hauling_note' => $data['loading_hauling_note'] ?? null,
                'dust_control_check' => $data['dust_control_check'],
                'dust_control_note' => $data['dust_control_note'] ?? null,
                'lighting_check' => $data['lighting_check'],
                'lighting_note' => $data['lighting_note'] ?? null,
                'housekeeping_check' => $data['housekeeping_check'],
                'housekeeping_note' => $data['housekeeping_note'] ?? null,
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

            KLKHLoadingPoint::create($dataToInsert);

            return redirect()->route('klkh.loading-point')->with('success', 'KLKH Loading Point berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.loading-point')->with('info', nl2br('KLKH Loading Point gagal dibuat..\n' . $th->getMessage()));
        }

    }

    public function delete($id)
    {
        try {
            KLKHLoadingPoint::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.loading-point')->with('success', 'KLKH Loading Point berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.loading-point')->with('info', nl2br('KLKH Loading Point gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHLoadingPoint::where('uuid', $uuid)->first();

        try {
            KLKHLoadingPoint::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH Loading Point berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Loading Point gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHLoadingPoint::where('uuid', $uuid)->first();

        try {
            KLKHLoadingPoint::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH Loading Point berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Loading Point gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHLoadingPoint::where('uuid', $uuid)->first();

        try {
            KLKHLoadingPoint::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH Loading Point berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Loading Point gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHLoadingPoint::where('uuid', $uuid)->first();

        try {
            KLKHLoadingPoint::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH Loading Point berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Loading Point gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
