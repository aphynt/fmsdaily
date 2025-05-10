<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\KLKHBatuBara;
use App\Models\Personal;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Ramsey\Uuid\Uuid;

class KLKHBatuBaraController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeBatuBara' => $request->all()]);

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


        $baseQuery = DB::table('klkh_batubara_t as bb')
        ->leftJoin('users as us', 'bb.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'bb.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'bb.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'bb.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'bb.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'bb.superintendent', '=', 'spt.NRP')
        ->select(
            'bb.id',
            'bb.uuid',
            'bb.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, bb.created_at, 120) as tanggal_pembuatan'),
            'bb.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'bb.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'bb.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'bb.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'bb.verified_foreman',
            'bb.verified_supervisor',
            'bb.verified_superintendent',
            'bb.date',
            'bb.time',
        )
        ->where('bb.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, bb.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

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
            $query->where('bb.foreman', Auth::user()->nik)
                  ->orWhere('bb.supervisor', Auth::user()->nik)
                  ->orWhere('bb.superintendent', Auth::user()->nik);
        });

        $bb = $baseQuery->get();



        return view('klkh.batu-bara.index', compact('bb'));
    }

    public function preview($uuid)
    {
        $bb = DB::table('klkh_batubara_t as bb')
        ->leftJoin('users as us', 'bb.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'bb.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'bb.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'bb.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'bb.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'bb.superintendent', '=', 'spt.NRP')
        ->select(
            'bb.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('bb.statusenabled', true)
        ->where('bb.uuid', $uuid)->first();

        if($bb == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $bb->verified_foreman = $bb->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_foreman) : null;
            $bb->verified_supervisor = $bb->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_supervisor) : null;
            $bb->verified_superintendent = $bb->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_superintendent) : null;
        }

        return view('klkh.batu-bara.preview', compact('bb'));
    }

    public function cetak($uuid)
    {
        $bb = DB::table('klkh_batubara_t as bb')
        ->leftJoin('users as us', 'bb.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'bb.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'bb.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'bb.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'bb.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'bb.superintendent', '=', 'spt.NRP')
        ->select(
            'bb.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('bb.statusenabled', true)
        ->where('bb.uuid', $uuid)->first();

        if($bb == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $bb->verified_foreman = $bb->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_foreman) : null;
            $bb->verified_supervisor = $bb->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_supervisor) : null;
            $bb->verified_superintendent = $bb->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_superintendent) : null;
        }

        return view('klkh.batu-bara.cetak', compact('bb'));
    }

    public function download($uuid)
    {
        $bb = DB::table('klkh_batubara_t as bb')
        ->leftJoin('users as us', 'bb.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'bb.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'bb.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'bb.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'bb.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'bb.superintendent', '=', 'spt.NRP')
        ->select(
            'bb.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('bb.statusenabled', true)
        ->where('bb.uuid', $uuid)->first();

        if($bb == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $bb->verified_foreman = $bb->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_foreman)) : null;
            $bb->verified_supervisor = $bb->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_supervisor)) : null;
            $bb->verified_superintendent = $bb->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $bb->nama_superintendent)) : null;
        }

        $pdf = PDF::loadView('klkh.batu-bara.download', compact('bb'));
        return $pdf->download('KLKH Batu Bara-'. $bb->date .'-'. $bb->shift .'-'. $bb->nama_pic .'.pdf');

        // return view('klkh.batu-bara.download', compact('bb'));
    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeBatuBara')['rangeStart']) || empty(session('requestTimeBatuBara')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeBatuBara')['rangeStart']);
            $end = new DateTime(session('requestTimeBatuBara')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $bb = DB::table('klkh_batubara_t as bb')
        ->leftJoin('users as us', 'bb.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'bb.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'bb.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'bb.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'bb.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'bb.superintendent', '=', 'spt.NRP')
        ->select(
            'bb.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('bb.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, bb.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($bb->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $bb = $bb->map(function($item) {
                // Modifikasi untuk setiap item dalam collection
                $item->verified_foreman = $item->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_foreman)) : null;
                $item->verified_supervisor = $item->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_supervisor)) : null;
                $item->verified_superintendent = $item->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_superintendent)) : null;

                return $item;
            });

        }

        $pdf = PDF::loadView('klkh.batu-bara.bundlepdf', compact('bb'));
        return $pdf->download('KLKH Batu Bara.pdf');

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
        return view('klkh.batu-bara.insert', compact('users'));
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
                    'loading_point_check' => $data['loading_point_check'] ?? null,
                    'loading_point_note' => $data['loading_point_note'] ?? null,
                    'permukaan_front_check' => $data['permukaan_front_check'] ?? null,
                    'permukaan_front_note' => $data['permukaan_front_note'] ?? null,
                    'tinggi_bench_check' => $data['tinggi_bench_check'] ?? null,
                    'tinggi_bench_note' => $data['tinggi_bench_note'] ?? null,
                    'lebar_loading_check' => $data['lebar_loading_check'] ?? null,
                    'lebar_loading_note' => $data['lebar_loading_note'] ?? null,
                    'drainase_check' => $data['drainase_check'] ?? null,
                    'drainase_note' => $data['drainase_note'] ?? null,
                    'penempatan_unit_check' => $data['penempatan_unit_check'] ?? null,
                    'penempatan_unit_note' => $data['penempatan_unit_note'] ?? null,
                    'pelabelan_seam_check' => $data['pelabelan_seam_check'] ?? null,
                    'pelabelan_seam_note' => $data['pelabelan_seam_note'] ?? null,
                    'lampu_unit_check' => $data['lampu_unit_check'] ?? null,
                    'lampu_unit_note' => $data['lampu_unit_note'] ?? null,
                    'unit_bersih_check' => $data['unit_bersih_check'] ?? null,
                    'unit_bersih_note' => $data['unit_bersih_note'] ?? null,
                    'penerangan_area_check' => $data['penerangan_area_check'] ?? null,
                    'penerangan_area_note' => $data['penerangan_area_note'] ?? null,
                    'housekeeping_check' => $data['housekeeping_check'] ?? null,
                    'housekeeping_note' => $data['housekeeping_note'] ?? null,
                    'pengukuran_roof_check' => $data['pengukuran_roof_check'] ?? null,
                    'pengukuran_roof_note' => $data['pengukuran_roof_note'] ?? null,
                    'cleaning_batubara_check' => $data['cleaning_batubara_check'] ?? null,
                    'cleaning_batubara_note' => $data['cleaning_batubara_note'] ?? null,
                    'genangan_air_check' => $data['genangan_air_check'] ?? null,
                    'genangan_air_note' => $data['genangan_air_note'] ?? null,
                    'big_coal_check' => $data['big_coal_check'] ?? null,
                    'big_coal_note' => $data['big_coal_note'] ?? null,
                    'stock_material_check' => $data['stock_material_check'] ?? null,
                    'stock_material_note' => $data['stock_material_note'] ?? null,
                    'lebar_jalan_angkut_check' => $data['lebar_jalan_angkut_check'] ?? null,
                    'lebar_jalan_angkut_note' => $data['lebar_jalan_angkut_note'] ?? null,
                    'lebar_jalan_tikungan_check' => $data['lebar_jalan_tikungan_check'] ?? null,
                    'lebar_jalan_tikungan_note' => $data['lebar_jalan_tikungan_note'] ?? null,
                    'super_elevasi_check' => $data['super_elevasi_check'] ?? null,
                    'super_elevasi_note' => $data['super_elevasi_note'] ?? null,
                    'safety_berm_check' => $data['safety_berm_check'] ?? null,
                    'safety_berm_note' => $data['safety_berm_note'] ?? null,
                    'tinggi_tanggul_check' => $data['tinggi_tanggul_check'] ?? null,
                    'tinggi_tanggul_note' => $data['tinggi_tanggul_note'] ?? null,
                    'safety_post_check' => $data['safety_post_check'] ?? null,
                    'safety_post_note' => $data['safety_post_note'] ?? null,
                    'drainase_genangan_air_check' => $data['drainase_genangan_air_check'] ?? null,
                    'drainase_genangan_air_note' => $data['drainase_genangan_air_note'] ?? null,
                    'median_jalan_check' => $data['median_jalan_check'] ?? null,
                    'median_jalan_note' => $data['median_jalan_note'] ?? null,
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

            KLKHBatuBara::create($dataToInsert);

            return redirect()->route('klkh.batubara')->with('success', 'KLKH Batubara berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.batubara')->with('info', nl2br('KLKH Batubara gagal dibuat..\n' . $th->getMessage()));
        }

    }

    public function delete($id)
    {
        try {
            KLKHBatuBara::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.batubara')->with('success', 'KLKH Batubara berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.batubara')->with('info', nl2br('KLKH Batubara gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHBatuBara::where('uuid', $uuid)->first();

        try {
            KLKHBatuBara::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH Batubara berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Batubara gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHBatuBara::where('uuid', $uuid)->first();

        try {
            KLKHBatuBara::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH Batubara berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Batubara gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHBatuBara::where('uuid', $uuid)->first();

        try {
            KLKHBatuBara::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH Batubara berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Batubara gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHBatuBara::where('uuid', $uuid)->first();

        try {
            KLKHBatuBara::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH Batubara berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Batubara gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
