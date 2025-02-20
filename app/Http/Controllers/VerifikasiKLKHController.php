<?php

namespace App\Http\Controllers;

use App\Models\KLKHBatuBara;
use App\Models\KLKHDisposal;
use App\Models\KLKHHaulRoad;
use App\Models\KLKHLoadingPoint;
use App\Models\KLKHLumpur;
use App\Models\KLKHOGS;
use App\Models\KLKHSimpangEmpat;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikasiKLKHController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd($request->all());

        if (empty($request->rangeStartVerif) || empty($request->rangeEndVerif)) {
            $time = Carbon::now();  // Mendapatkan waktu saat ini menggunakan Carbon

            // Shift siang dimulai pukul 06:30 dan berakhir pukul 18:30
            $startDateMorning = $time->copy()->setTime(6, 30, 0); // 06:30:00 hari ini
            $endDateMorning = $time->copy()->setTime(18, 30, 0); // 18:30:00 hari ini

            // Shift malam dimulai pukul 18:30 hari ini dan berakhir pukul 06:30 hari berikutnya
            $startDateNight = $time->copy()->setTime(18, 30, 0); // 18:30:00 hari ini
            $endDateNight = $time->copy()->setTime(6, 30, 0); // 06:30:00 besok

            // Pilih shift berdasarkan waktu saat ini (siang atau malam)
            if ($time->hour >= 18 && $time->minute >= 30 && $time->hour <= 6 && $time->minute >= 30) {
                // Jika sudah lewat jam 18:30, gunakan shift malam
                $endDateNight->addDay();
                $start = new DateTime($startDateNight->format('Y-m-d\TH:i:s'));
                $end = new DateTime($endDateNight->format('Y-m-d\TH:i:s'));
            } else {
                // Jika belum lewat jam 18:30, gunakan shift siang
                $start = new DateTime($startDateMorning->format('Y-m-d\TH:i:s'));
                $end = new DateTime($endDateMorning->format('Y-m-d\TH:i:s'));
            }
        } else {
            // Jika parameter rangeStartVerif dan rangeEndVerif ada di URL, gunakan nilai tersebut
            $start = new DateTime($request->rangeStartVerif);
            $end = new DateTime($request->rangeEndVerif);
        }

        // Format waktu sesuai dengan format yang diinginkan
        $startTimeFormatted = $start->format('Y-m-d H:i:s');
        $endTimeFormatted = $end->format('Y-m-d H:i:s');

        // dd($startTimeFormatted);


        $loading = DB::table('klkh_loadingpoint_t as lp')
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
            DB::raw("'LOADING POINT' as source_table")
        )
        ->where('lp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $loading->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $loading->orWhere('pic', Auth::user()->id);
        }

        $haulroad = DB::table('klkh_haulroad_t as hr')
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
            DB::raw("'HAUL ROAD' as source_table")
        )
        ->where('hr.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, hr.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $haulroad->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $haulroad->orWhere('pic', Auth::user()->id);
        }

        $disposal = DB::table('klkh_disposal_t as dp')
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
            DB::raw("'DISPOSAL/DUMPING POINT' as source_table")
        )
        ->where('dp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, dp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $disposal->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $disposal->orWhere('pic', Auth::user()->id);
        }

        $lumpur = DB::table('klkh_lumpur_t as lum')
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
            DB::raw("'DUMPING DIKOLAM AIR/LUMPUR' as source_table")
        )
        ->where('lum.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lum.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $lumpur->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $lumpur->orWhere('pic', Auth::user()->id);
        }

        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'ogs.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'ogs.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'ogs.superintendent', '=', 'spt.NRP')
        ->select(
            'ogs.id',
            'ogs.uuid',
            'ogs.pic as pic_id',
            'us.name as pic',
            DB::raw('CONVERT(varchar, ogs.created_at, 120) as tanggal_pembuatan'),
            'ogs.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'ogs.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'ogs.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'ogs.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'ogs.verified_foreman',
            'ogs.verified_supervisor',
            'ogs.verified_superintendent',
            'ogs.date',
            'ogs.time',
            DB::raw("'OGS' as source_table")
        )
        ->where('ogs.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, ogs.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $ogs->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $ogs->orWhere('pic', Auth::user()->id);
        }

        $batubara = DB::table('klkh_batubara_t as lp')
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
            DB::raw("'BATU BARA' as source_table")
        )
        ->where('lp.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $batubara->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $batubara->orWhere('pic', Auth::user()->id);
        }

        $simpangempat = DB::table('klkh_simpangempat_t as se')
        ->leftJoin('users as us', 'se.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'se.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'se.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'se.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'se.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'se.superintendent', '=', 'spt.NRP')
        ->select(
            'se.id',
            'se.uuid',
            'se.pic as pic_id',
            'us.name as pic',
            DB::raw('CONVERT(varchar, se.created_at, 120) as tanggal_pembuatan'),
            'se.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'se.foreman as nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'se.supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'se.superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'se.verified_foreman',
            'se.verified_supervisor',
            'se.verified_superintendent',
            'se.date',
            'se.time',
            DB::raw("'SIMPANG EMPAT' as source_table")
        )
        ->where('se.statusenabled', true)
        ->whereBetween(DB::raw('CONVERT(varchar, se.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'])) {
            $simpangempat->where(strtolower(Auth::user()->role), Auth::user()->nik);
        } elseif (Auth::user()->role == 'ADMIN') {
            $simpangempat->orWhere('pic', Auth::user()->id);
        }

        //Gabung Table
        $combinedQuery = $loading->unionAll($haulroad)->unionAll($disposal)->unionAll($lumpur)->unionAll($ogs)->unionAll($batubara)->unionAll($simpangempat);


        $combinedQuery = $combinedQuery->get()->groupBy('source_table');

        session(['data_verified' => $combinedQuery]);


        return view('klkh.index', compact('combinedQuery'));
    }

    public function all(Request $request)
    {
        $data = session('data_verified');

        if (Auth::user()->role == 'SUPERVISOR') {
            foreach ($data as $category => $items) {
                foreach ($items as $item) {
                    if (isset($item->uuid)) {
                        //Loading Point
                        $loadingpoint = KLKHLoadingPoint::where('uuid', $item->uuid)->first();
                        if ($category == 'LOADING POINT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Haul Road
                        $loadingpoint = KLKHHaulRoad::where('uuid', $item->uuid)->first();
                        if ($category == 'HAUL ROAD' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Disposal
                        $loadingpoint = KLKHDisposal::where('uuid', $item->uuid)->first();
                        if ($category == 'DISPOSAL/DUMPING POINT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Lumpur
                        $loadingpoint = KLKHLumpur::where('uuid', $item->uuid)->first();
                        if ($category == 'DUMPING DIKOLAM AIR/LUMPUR' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //OGS
                        $loadingpoint = KLKHOGS::where('uuid', $item->uuid)->first();
                        if ($category == 'OGS' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Batu Bara
                        $loadingpoint = KLKHBatuBara::where('uuid', $item->uuid)->first();
                        if ($category == 'BATU BARA' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Simpang Empat
                        $loadingpoint = KLKHSimpangEmpat::where('uuid', $item->uuid)->first();
                        if ($category == 'SIMPANG EMPAT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_supervisor' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }
        }

        if (Auth::user()->role == 'SUPERINTENDENT') {
            foreach ($data as $category => $items) {
                foreach ($items as $item) {
                    if (isset($item->uuid)) {
                        //Loading Point
                        $loadingpoint = KLKHLoadingPoint::where('uuid', $item->uuid)->first();
                        if ($category == 'LOADING POINT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Haul Road
                        $loadingpoint = KLKHHaulRoad::where('uuid', $item->uuid)->first();
                        if ($category == 'HAUL ROAD' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Disposal
                        $loadingpoint = KLKHDisposal::where('uuid', $item->uuid)->first();
                        if ($category == 'DISPOSAL/DUMPING POINT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Lumpur
                        $loadingpoint = KLKHLumpur::where('uuid', $item->uuid)->first();
                        if ($category == 'DUMPING DIKOLAM AIR/LUMPUR' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //OGS
                        $loadingpoint = KLKHOGS::where('uuid', $item->uuid)->first();
                        if ($category == 'OGS' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Batu Bara
                        $loadingpoint = KLKHBatuBara::where('uuid', $item->uuid)->first();
                        if ($category == 'BATU BARA' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }

                        //Simpang Empat
                        $loadingpoint = KLKHSimpangEmpat::where('uuid', $item->uuid)->first();
                        if ($category == 'SIMPANG EMPAT' && $loadingpoint) {
                            $loadingpoint->update([
                                'verified_superintendent' => (string) Auth::user()->nik,
                                'updated_by' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Success');
    }

}
