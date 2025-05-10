<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\KLKHHaulRoad;
use App\Models\Personal;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class KLKHHaulRoadController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeHaulRoad' => $request->all()]);

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


        $baseQuery = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->select(
            'hr.id',
            'hr.uuid',
            'hr.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, hr.created_at, 120) as tanggal_pembuatan'),
            'hr.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'hr.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'hr.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'hr.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'hr.verified_foreman',
            'hr.verified_supervisor',
            'hr.verified_superintendent',
            'hr.date',
            'hr.time',
        )
        ->where('hr.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, hr.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

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
            $query->where('hr.foreman', Auth::user()->nik)
                  ->orWhere('hr.supervisor', Auth::user()->nik)
                  ->orWhere('hr.superintendent', Auth::user()->nik);
        });

        $haul = $baseQuery->get();

        return view('klkh.haul-road.index', compact('haul'));
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
        return view('klkh.haul-road.insert', compact('users'));
    }

    public function cetak($uuid)
    {
        $hr = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->select(
            'hr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('hr.statusenabled', true)
        ->where('hr.uuid', $uuid)->first();

        if($hr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $hr->verified_foreman = $hr->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_foreman) : null;
            $hr->verified_supervisor = $hr->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_supervisor) : null;
            $hr->verified_superintendent = $hr->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_superintendent) : null;
        }

        return view('klkh.haul-road.cetak', compact('hr'));
    }

    public function download($uuid)
    {
        $hr = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->select(
            'hr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('hr.statusenabled', true)
        ->where('hr.uuid', $uuid)->first();

        if($hr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $hr->verified_foreman = $hr->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_foreman)) : null;
            $hr->verified_supervisor = $hr->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_supervisor)) : null;
            $hr->verified_superintendent = $hr->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_superintendent)) : null;
        }

        $pdf = PDF::loadView('klkh.haul-road.download', compact('hr'));
        return $pdf->download('KLKH Haul Road-'. $hr->date .'-'. $hr->shift .'-'. $hr->nama_pic .'.pdf');

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
                    'road_width_check' => $data['road_width_check'],
                    'road_width_note' => $data['road_width_note'] ?? null,
                    'curve_width_check' => $data['curve_width_check'],
                    'curve_width_note' => $data['curve_width_note'] ?? null,
                    'super_elevation_check' => $data['super_elevation_check'],
                    'super_elevation_note' => $data['super_elevation_note'] ?? null,
                    'safety_berm_check' => $data['safety_berm_check'],
                    'safety_berm_note' => $data['safety_berm_note'] ?? null,
                    'tanggul_check' => $data['tanggul_check'],
                    'tanggul_note' => $data['tanggul_note'] ?? null,
                    'safety_patok_check' => $data['safety_patok_check'],
                    'safety_patok_note' => $data['safety_patok_note'] ?? null,
                    'drainage_check' => $data['drainage_check'],
                    'drainage_note' => $data['drainage_note'] ?? null,
                    'median_check' => $data['median_check'],
                    'median_note' => $data['median_note'] ?? null,
                    'intersection_check' => $data['intersection_check'],
                    'intersection_note' => $data['intersection_note'] ?? null,
                    'traffic_sign_check' => $data['traffic_sign_check'],
                    'traffic_sign_note' => $data['traffic_sign_note'] ?? null,
                    'night_work_sign_check' => $data['night_work_sign_check'],
                    'night_work_sign_note' => $data['night_work_sign_note'] ?? null,
                    'road_condition_check' => $data['road_condition_check'],
                    'road_condition_note' => $data['road_condition_note'] ?? null,
                    'divider_check' => $data['divider_check'],
                    'divider_note' => $data['divider_note'] ?? null,
                    'haul_route_check' => $data['haul_route_check'],
                    'haul_route_note' => $data['haul_route_note'] ?? null,
                    'dust_control_check' => $data['dust_control_check'],
                    'dust_control_note' => $data['dust_control_note'] ?? null,
                    'intersection_officer_check' => $data['intersection_officer_check'],
                    'intersection_officer_note' => $data['intersection_officer_note'] ?? null,
                    'red_light_check' => $data['red_light_check'],
                    'red_light_note' => $data['red_light_note'] ?? null,
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

            KLKHHaulRoad::create($dataToInsert);

            return redirect()->route('klkh.haul-road')->with('success', 'KLKH Haul Road berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.haul-road')->with('info', nl2br('KLKH Haul Road gagal dibuat..\n' . $th->getMessage()));
        }
    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeHaulRoad')['rangeStart']) || empty(session('requestTimeHaulRoad')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeHaulRoad')['rangeStart']);
            $end = new DateTime(session('requestTimeHaulRoad')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $hr = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->select(
            'hr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('hr.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, hr.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($hr->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $hr = $hr->map(function($item) {
                // Modifikasi untuk setiap item dalam collection
                $item->verified_foreman = $item->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_foreman)) : null;
                $item->verified_supervisor = $item->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_supervisor)) : null;
                $item->verified_superintendent = $item->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_superintendent)) : null;

                return $item;
            });

        }

        $pdf = PDF::loadView('klkh.haul-road.bundlepdf', compact('hr'));
        return $pdf->download('KLKH Haul Road.pdf');

    }

    public function preview($uuid)
    {
        $hr = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->select(
            'hr.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('hr.statusenabled', true)
        ->where('hr.uuid', $uuid)->first();

        if($hr == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $hr->verified_foreman = $hr->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_foreman) : null;
            $hr->verified_supervisor = $hr->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_supervisor) : null;
            $hr->verified_superintendent = $hr->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $hr->nama_superintendent) : null;
        }

        return view('klkh.haul-road.preview', compact('hr'));
    }

    public function delete($id)
    {
        try {
            KLKHHaulRoad::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.haul-road')->with('success', 'KLKH Haul Road berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.haul-road')->with('info', nl2br('KLKH Haul Road gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHHaulRoad::where('uuid', $uuid)->first();

        try {
            KLKHHaulRoad::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH Haul Road berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Haul Road gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHHaulRoad::where('uuid', $uuid)->first();

        try {
            KLKHHaulRoad::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH Haul Road berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Haul Road gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHHaulRoad::where('uuid', $uuid)->first();

        try {
            KLKHHaulRoad::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH Haul Road berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Haul Road gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHHaulRoad::where('uuid', $uuid)->first();

        try {
            KLKHHaulRoad::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH Haul Road berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Haul Road gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
