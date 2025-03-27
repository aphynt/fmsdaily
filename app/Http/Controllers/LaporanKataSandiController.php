<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\PRDKataSandi;
use App\Models\PRDKataSandiUnit;
use App\Models\Shift;
use App\Models\Unit;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKataSandiController extends Controller
{
    //
    public function index()
    {
        $shift = Shift::where('statusenabled', true)->get();
        $unit = Unit::select('VHC_ID')->where('VHC_ID', 'LIKE', 'HD%')->get();

        return view('laporan-kata-sandi.index', compact('shift', 'unit'));
    }

    public function post(Request $request)
    {
        // return $request->all();

        try {

                $kataSandi = PRDKataSandi::create([
                    'uuid' => (string) Uuid::uuid4()->toString(),
                    'foreman_id' => Auth::user()->id,
                    'kata_sandi' => $request->input('kataSandi'),
                    'date' => $request->input('date'),
                    'shift' => $request->input('shift'),
                ]);

            foreach ($request->input('noUnit') as $key => $unit) {
                PRDKataSandiUnit::create([
                    'uuid' => (string) Uuid::uuid4()->toString(),
                    'kata_sandi_uuid' => $kataSandi->uuid,
                    'kata_sandi_id' => $kataSandi->id,
                    'no_unit' => $unit,
                    'jam_monitor' => $request->input('jamMonitor')[$key],
                    'keterangan' => $request->input('keterangan')[$key],
                ]);

            }

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikirim!',
                'data' => $kataSandi,
            ]);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Gagal menyimpan laporan: ' . $th->getMessage()], 500);
        }

    }

    public function show(Request $request)
    {
        session(['requestTimeLaporanKataSandi' => $request->all()]);

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

        $kataSandi = DB::table('PRD_KATASANDI as ks')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ks.id',
            'ks.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'sh.keterangan as shift',
        )
        ->whereBetween('ks.date', [$startTimeFormatted, $endTimeFormatted])
        ->where('ks.statusenabled', true);


        $kataSandi = $kataSandi->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('us.nik', Auth::user()->nik);
            }
        });

        $kataSandi = $kataSandi->get();

        // dd($kataSandi);

        // $kataSandi = DB::table('PRD_KATASANDI as ks')
        // ->leftJoin('PRD_KATASANDI_UNIT as us', 'se.kata_sandi_id', '=', 'ks.id')
        return view('laporan-kata-sandi.show', compact('kataSandi'));
    }

    public function preview($uuid){

        $kataSandi = DB::table('PRD_KATASANDI as ks')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ks.id',
            'ks.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'sh.keterangan as shift',
        )->where('uuid', $uuid)->where('ks.statusenabled', true)->first();

        if($kataSandi == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        $kUnit = DB::table('PRD_KATASANDI_UNIT as ksu')
        ->leftJoin('PRD_KATASANDI as ks', 'ksu.kata_sandi_uuid', '=', 'ks.uuid')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ksu.id',
            'ksu.uuid',
            'ksu.no_unit',
            'sh.keterangan as shift',
            'ksu.jam_monitor',
            'ksu.keterangan',

        )->where('kata_sandi_uuid', $uuid)->where('ks.statusenabled', true)->get();

        $data = [
            'kataSandi' => $kataSandi,
            'kataSandiUnit' => $kUnit,
        ];

        return view('laporan-kata-sandi.preview', compact('data'));
    }

    public function cetak($uuid){

        $kataSandi = DB::table('PRD_KATASANDI as ks')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ks.id',
            'ks.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'sh.keterangan as shift',
        )->where('uuid', $uuid)->where('ks.statusenabled', true)->first();

        if($kataSandi == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        $kUnit = DB::table('PRD_KATASANDI_UNIT as ksu')
        ->leftJoin('PRD_KATASANDI as ks', 'ksu.kata_sandi_uuid', '=', 'ks.uuid')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ksu.id',
            'ksu.uuid',
            'ksu.no_unit',
            'sh.keterangan as shift',
            'ksu.jam_monitor',
            'ksu.keterangan',

        )->where('kata_sandi_uuid', $uuid)->where('ks.statusenabled', true)->get();

        $data = [
            'kataSandi' => $kataSandi,
            'kataSandiUnit' => $kUnit,
        ];

        return view('laporan-kata-sandi.cetak', compact('data'));
    }

    public function pdf($uuid){

        $kataSandi = DB::table('PRD_KATASANDI as ks')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ks.id',
            'ks.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'sh.keterangan as shift',
        )->where('uuid', $uuid)->where('ks.statusenabled', true)->first();

        if($kataSandi == null){
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }

        $kUnit = DB::table('PRD_KATASANDI_UNIT as ksu')
        ->leftJoin('PRD_KATASANDI as ks', 'ksu.kata_sandi_uuid', '=', 'ks.uuid')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ksu.id',
            'ksu.uuid',
            'ksu.no_unit',
            'sh.keterangan as shift',
            'ksu.jam_monitor',
            'ksu.keterangan',

        )->where('kata_sandi_uuid', $uuid)->where('ks.statusenabled', true)->get();

        $data = [
            'kataSandi' => $kataSandi,
            'kataSandiUnit' => $kUnit,
        ];

        $pdf = PDF::loadView('laporan-kata-sandi.pdf', compact('data'));
        return $pdf->download('Laporan Kata Sandi-'. $kataSandi->tanggal .'-'. $kataSandi->shift .'-'. $kataSandi->pic .'.pdf');
    }

    public function jamMonitor(Request $request)
    {

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

        $data = DB::table('PRD_KATASANDI_UNIT as ksu')
        ->leftJoin('PRD_KATASANDI as ks', 'ksu.kata_sandi_uuid', '=', 'ks.uuid')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'ksu.id',
            'ksu.uuid',
            'ksu.no_unit',
            'sh.keterangan as shift',
            'ksu.jam_monitor',
            'ksu.keterangan',

        )
        ->whereBetween('ks.date', [$startTimeFormatted, $endTimeFormatted])
        ->where('ks.statusenabled', true)->get();

        return view('laporan-kata-sandi.jamMonitor', compact('data'));
    }

    public function delete($uuid)
    {

        $kataSandi = DB::table('PRD_KATASANDI as ks')
        ->leftJoin('users as us', 'ks.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'ks.shift', '=', 'sh.id')
        ->select(
            'ks.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'ks.created_at as tanggal_dibuat',
            'ks.date as tanggal',
            'ks.kata_sandi',
            'sh.keterangan as shift',
        )->where('uuid', $uuid)->first();

        try {

            Log::create([
                'tanggal_loging' => now(),
                'jenis_loging' => 'Laporan Kata Sandi',
                'nama_user' => Auth::user()->id,
                'nik' => Auth::user()->nik,
                'keterangan' => 'Hapus laporan kata sandi dengan PIC: '. $kataSandi->pic . ', tanggal pembuatan: '. $kataSandi->tanggal .
                ', shift: '. $kataSandi->shift,
            ]);

            PRDKataSandi::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            PRDKataSandiUnit::where('kata_sandi_uuid', $uuid)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);


            return redirect()->back()->with('success', 'Laporan Kata Sandi berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', $th->getMessage());
        }
    }
}
