<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\KLKHDisposal;
use App\Models\Personal;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class KLKHDisposalController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['requestTimeDisposal' => $request->all()]);

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


        $baseQuery = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->select(
            'dp.id',
            'dp.uuid',
            'dp.pic as pic_id',
            'us.name as pic',
            'us.nik as nik_pic',
            DB::raw('CONVERT(varchar, dp.created_at, 120) as tanggal_pembuatan'),
            'dp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'dp.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dp.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dp.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'dp.verified_foreman',
            'dp.verified_supervisor',
            'dp.verified_superintendent',
            'dp.date',
            'dp.time',
        )
        ->where('dp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, dp.date, 23)'), [$startTimeFormatted, $endTimeFormatted]);

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
            $query->where('dp.foreman', Auth::user()->nik)
                  ->orWhere('dp.supervisor', Auth::user()->nik)
                  ->orWhere('dp.superintendent', Auth::user()->nik);
        });

        $disposal = $baseQuery->get();

        return view('klkh.disposal.index', compact('disposal'));
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
        return view('klkh.disposal.insert', compact('users'));
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
                    'dumping_point_1' => $data['dumping_point_1'],
                    'dumping_point_1_note' => $data['dumping_point_1_note'] ?? null,
                    'dumping_point_2' => $data['dumping_point_2'],
                    'dumping_point_2_note' => $data['dumping_point_2_note'] ?? null,
                    'dumping_point_3' => $data['dumping_point_3'],
                    'dumping_point_3_note' => $data['dumping_point_3_note'] ?? null,
                    'dumping_point_4' => $data['dumping_point_4'],
                    'dumping_point_4_note' => $data['dumping_point_4_note'] ?? null,
                    'dumping_point_5' => $data['dumping_point_5'],
                    'dumping_point_5_note' => $data['dumping_point_5_note'] ?? null,
                    'dumping_point_6' => $data['dumping_point_6'],
                    'dumping_point_6_note' => $data['dumping_point_6_note'] ?? null,
                    'dumping_point_7' => $data['dumping_point_7'],
                    'dumping_point_7_note' => $data['dumping_point_7_note'] ?? null,
                    'dumping_point_8' => $data['dumping_point_8'],
                    'dumping_point_8_note' => $data['dumping_point_8_note'] ?? null,
                    'dumping_point_9' => $data['dumping_point_9'],
                    'dumping_point_9_note' => $data['dumping_point_9_note'] ?? null,
                    'dumping_point_10' => $data['dumping_point_10'],
                    'dumping_point_10_note' => $data['dumping_point_10_note'] ?? null,
                    'dumping_point_11' => $data['dumping_point_11'],
                    'dumping_point_11_note' => $data['dumping_point_11_note'] ?? null,
                    'dumping_point_12' => $data['dumping_point_12'],
                    'dumping_point_12_note' => $data['dumping_point_12_note'] ?? null,
                    'dumping_point_13' => $data['dumping_point_13'],
                    'dumping_point_13_note' => $data['dumping_point_13_note'] ?? null,
                    'dumping_point_14' => $data['dumping_point_14'],
                    'dumping_point_14_note' => $data['dumping_point_14_note'] ?? null,
                    'dumping_point_15' => $data['dumping_point_15'],
                    'dumping_point_15_note' => $data['dumping_point_15_note'] ?? null,
                    'dumping_point_16' => $data['dumping_point_16'],
                    'dumping_point_16_note' => $data['dumping_point_16_note'] ?? null,
                    'dumping_point_17' => $data['dumping_point_17'],
                    'dumping_point_17_note' => $data['dumping_point_17_note'] ?? null,
                    'dumping_point_18' => $data['dumping_point_18'],
                    'dumping_point_18_note' => $data['dumping_point_18_note'] ?? null,
                    'dumping_point_19' => $data['dumping_point_19'],
                    'dumping_point_19_note' => $data['dumping_point_19_note'] ?? null,
                    'dumping_point_20' => $data['dumping_point_20'],
                    'dumping_point_20_note' => $data['dumping_point_20_note'] ?? null,
                    'dumping_point_21' => $data['dumping_point_21'],
                    'dumping_point_21_note' => $data['dumping_point_21_note'] ?? null,
                    'dumping_point_22' => $data['dumping_point_22'],
                    'dumping_point_22_note' => $data['dumping_point_22_note'] ?? null,
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

            KLKHDisposal::create($dataToInsert);

            return redirect()->route('klkh.disposal')->with('success', 'KLKH Disposal/Dumping Point berhasil dibuat');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.disposal')->with('info', nl2br('KLKH Disposal/Dumping Point gagal dibuat..\n' . $th->getMessage()));
        }
    }

    public function cetak($uuid)
    {
        $dp = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->select(
            'dp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('dp.statusenabled', true)
        ->where('dp.uuid', $uuid)->first();

        if($dp == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $dp->verified_foreman = $dp->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_foreman) : null;
            $dp->verified_supervisor = $dp->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_supervisor) : null;
            $dp->verified_superintendent = $dp->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_superintendent) : null;
        }

        return view('klkh.disposal.cetak', compact('dp'));
    }

    public function download($uuid)
    {
        $dp = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->select(
            'dp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('dp.statusenabled', true)
        ->where('dp.uuid', $uuid)->first();

        if($dp == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $dp;

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

        $pdf = PDF::loadView('klkh.disposal.download', compact('dp'));
        return $pdf->download('KLKH Dumping Point-'. $dp->date .'-'. $dp->shift .'-'. $dp->nama_pic .'.pdf');

    }

    public function bundlepdf(Request $request)
    {

        if (empty(session('requestTimeDisposal')['rangeStart']) || empty(session('requestTimeDisposal')['rangeEnd'])){
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate = $time->format('Y-m-d');

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime(session('requestTimeDisposal')['rangeStart']);
            $end = new DateTime(session('requestTimeDisposal')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $dp = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->select(
            'dp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('dp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, dp.date, 23)'), [$startTimeFormatted, $endTimeFormatted])->get();


        if ($dp->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $dp = $dp->map(function($item) {
                // Modifikasi untuk setiap item dalam collection
                $item->verified_foreman = $item->verified_foreman != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_foreman)) : null;
                $item->verified_supervisor = $item->verified_supervisor != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_supervisor)) : null;
                $item->verified_superintendent = $item->verified_superintendent != null ? base64_encode(QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->nama_superintendent)) : null;

                return $item;
            });

        }

        $pdf = PDF::loadView('klkh.disposal.bundlepdf', compact('dp'));
        return $pdf->download('KLKH Dumping Point.pdf');

    }

    public function preview($uuid)
    {
        $dp = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->select(
            'dp.*',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'us.name as nama_pic',
            'gl.PERSONALNAME as nama_foreman',
            'spv.PERSONALNAME as nama_supervisor',
            'spt.PERSONALNAME as nama_superintendent'
            )
        ->where('dp.statusenabled', true)
        ->where('dp.uuid', $uuid)->first();

        if($dp == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $dp->verified_foreman = $dp->verified_foreman != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_foreman) : null;
            $dp->verified_supervisor = $dp->verified_supervisor != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_supervisor) : null;
            $dp->verified_superintendent = $dp->verified_superintendent != null ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $dp->nama_superintendent) : null;
        }

        return view('klkh.disposal.preview', compact('dp'));
    }

    public function delete($id)
    {
        try {
            KLKHDisposal::where('id', $id)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->route('klkh.disposal')->with('success', 'KLKH Disposal berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->route('klkh.disposal')->with('info', nl2br('KLKH Disposal gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function verifiedAll(Request $request, $uuid)
    {
        $klkh =  KLKHDisposal::where('uuid', $uuid)->first();

        try {
            KLKHDisposal::where('id', $klkh->id)->update([
                'verified_foreman' => $klkh->foreman,
                'verified_supervisor' => $klkh->supervisor,
                'verified_superintendent' => $klkh->superintendent,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_all,
                'catatan_verified_supervisor' => $request->catatan_verified_all,
                'catatan_verified_superintendent' => $request->catatan_verified_all,
            ]);

            return redirect()->back()->with('success', 'KLKH Disposal berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Disposal gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedForeman(Request $request, $uuid)
    {
        $klkh =  KLKHDisposal::where('uuid', $uuid)->first();

        try {
            KLKHDisposal::where('id', $klkh->id)->update([
                'verified_foreman' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_foreman' => $request->catatan_verified_foreman,
            ]);

            return redirect()->back()->with('success', 'KLKH Disposal berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Disposal gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSupervisor(Request $request, $uuid)
    {
        $klkh =  KLKHDisposal::where('uuid', $uuid)->first();

        try {
            KLKHDisposal::where('id', $klkh->id)->update([
                'verified_supervisor' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_supervisor' => $request->catatan_verified_supervisor,
            ]);

            return redirect()->back()->with('success', 'KLKH Disposal berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Disposal gagal diverifikasi..\n' . $th->getMessage()));
        }
    }

    public function verifiedSuperintendent(Request $request, $uuid)
    {
        $klkh =  KLKHDisposal::where('uuid', $uuid)->first();

        try {
            KLKHDisposal::where('id', $klkh->id)->update([
                'verified_superintendent' => (string)Auth::user()->nik,
                'updated_by' => Auth::user()->id,
                'catatan_verified_superintendent' => $request->catatan_verified_superintendent,
            ]);

            return redirect()->back()->with('success', 'KLKH Disposal berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Disposal gagal diverifikasi..\n' . $th->getMessage()));
        }
    }
}
