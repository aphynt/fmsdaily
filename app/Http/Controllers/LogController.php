<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    //
    public function index(Request $request)
    {

        // dd($request->all());

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

        $user = User::all();
        $jenis = collect([
            (object)['jenis' => 'KLKH'],
            (object)['jenis' => 'Laporan Kerja'],
            (object)['jenis' => 'User'],
            (object)['jenis' => 'Alat Support'],
        ]);

        $log = DB::table('HIS_LOG as lg')->leftJoin('users as us', 'lg.nama_user', 'us.id')
        ->select(
            'lg.tanggal_loging',
            'lg.jenis_loging',
            'us.name as nama_user',
            'lg.nik',
            'lg.keterangan',
            )->whereBetween('lg.tanggal_loging', [$startTimeFormatted, $endTimeFormatted]);
        if (!empty($request->jenis_log)){
            $log = $log->where('lg.jenis_loging', $request->jenis_log);
        }
        if (!empty($request->nik)) {
            $log = $log->where('lg.keterangan', 'LIKE', '%'.$request->nik.'%');
        }
        if (!empty($request->nama_user)){
            $log = $log->where('lg.nama_user', $request->nama_user);
        }
        $log = $log->get();

        $data = [
            'user' => $user,
            'jenis' => $jenis,
            'log' => $log,
        ];

        return view('log.index', compact('data'));
    }

    public function search(Request $request)
    {
        return redirect()->back()->with('info', 'Maaf, fitur masih dalam tahap pengembangan');
    }
}
