<?php

namespace App\Http\Controllers;

use App\Models\KLKHLoadingPoint;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifikasiKLKHLoadingPointController extends Controller
{
    //
    public function index(Request $request)
    {
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
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lp.superintendent', '=', 'spt.NRP')
        ->select(
            'lp.id',
            'lp.uuid',
            'lp.pic as pic_id',
            'us.name as pic',
            DB::raw('CONVERT(varchar, lp.created_at, 120) as tanggal_pembuatan'),
            'lp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'lp.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'lp.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'lp.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'lp.verified_foreman',
            'lp.verified_supervisor',
            'lp.verified_superintendent',
            'lp.date',
            'lp.time',
        )
        ->where('lp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lp.created_at, 23)'), [$startTimeFormatted, $endTimeFormatted]);



        if (Auth::user()->role == 'FOREMAN') {
            $baseQuery->where('foreman', Auth::user()->nik);
        }
        if (Auth::user()->role == 'SUPERVISOR') {
            $baseQuery->where('supervisor', Auth::user()->nik);
        }
        if (Auth::user()->role == 'SUPERINTENDENT') {
            $baseQuery->where('superintendent', Auth::user()->nik);
        }
        if (Auth::user()->role == 'ADMIN') {
            $baseQuery->orWhere('pic', Auth::user()->id);
        }

        $loading = $baseQuery->get();

        session([
            'rangeStart' => $startTimeFormatted,
            'rangeEnd' => $endTimeFormatted,
            'data' => $loading
        ]);

        return view('verifikasi.klkh.loading-point.index', compact('loading'));
    }

    public function all(Request $request)
    {
        $rangeStart = session('rangeStart');
        $rangeEnd = session('rangeEnd');
        $acc = session('data')->map(function ($item) {
            return [
                'id' => $item->id,
                'nik_foreman' => $item->nik_foreman,
                'nik_supervisor' => $item->nik_supervisor,
                'nik_superintendent' => $item->nik_superintendent,
            ];
        });

        try {
            if(Auth::user()->role == 'SUPERVISOR'){
                KLKHLoadingPoint::where('supervisor', Auth::user()->nik)
                ->whereBetween('date', [$rangeStart, $rangeEnd])
                ->update([
                    'verified_supervisor' => Auth::user()->nik,
                    'updated_by' => Auth::user()->id,
                ]);
            }

            if(Auth::user()->role == 'SUPERINTENDENT'){
                KLKHLoadingPoint::where('superintendent', Auth::user()->nik)
                ->whereBetween('date', [$rangeStart, $rangeEnd])
                ->update([
                    'verified_superintendent' => Auth::user()->nik,
                    'updated_by' => Auth::user()->id,
                ]);
            }

            if(Auth::user()->role == 'ADMIN'){
                foreach ($acc as $item) {
                    KLKHLoadingPoint::whereBetween('date', [$rangeStart, $rangeEnd])
                        ->where('id', $item['id'])  // Menambahkan kondisi berdasarkan ID
                        ->update([
                            'verified_foreman' => $item['nik_foreman'],
                            'verified_supervisor' => $item['nik_supervisor'],
                            'verified_superintendent' => $item['nik_superintendent'],
                            'updated_by' => Auth::user()->id,
                        ]);
                }
            }


            return redirect()->back()->with('success', 'KLKH Batubara berhasil diverifikasi');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('KLKH Batubara gagal diverifikasi..\n' . $th->getMessage()));
        }

        dd($rangeStart);
    }
}
