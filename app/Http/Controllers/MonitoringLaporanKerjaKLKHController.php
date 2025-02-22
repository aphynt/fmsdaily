<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringLaporanKerjaKLKHController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd($request->all());
        // $today = Carbon::today();  // Mendapatkan tanggal hari ini
        // $year = strval($today->year);  // Tahun dalam format angka (contoh: 2025)
        // $month = strval($today->month);  // Bulan dalam format angka, dikonversi ke string (contoh: "1" untuk Januari)
        // $dayOfMonth = $today->day;

        // if (empty($request->rangeStartVerif) || empty($request->rangeEndVerif)) {
        //     $time = Carbon::now();  // Mendapatkan waktu saat ini menggunakan Carbon

        //     // Shift siang dimulai pukul 06:30 dan berakhir pukul 18:30
        //     $startDateMorning = $time->copy()->setTime(6, 30, 0); // 06:30:00 hari ini
        //     $endDateMorning = $time->copy()->setTime(18, 30, 0); // 18:30:00 hari ini

        //     // Shift malam dimulai pukul 18:30 hari ini dan berakhir pukul 06:30 hari berikutnya
        //     $startDateNight = $time->copy()->setTime(18, 30, 0); // 18:30:00 hari ini
        //     $endDateNight = $time->copy()->setTime(6, 30, 0); // 06:30:00 besok

        //     // Pilih shift berdasarkan waktu saat ini (siang atau malam)
        //     if ($time->hour >= 18 && $time->minute >= 30 && $time->hour <= 6 && $time->minute >= 30) {
        //         // Jika sudah lewat jam 18:30, gunakan shift malam
        //         $endDateNight->addDay();
        //         $start = new DateTime($startDateNight->format('Y-m-d\TH:i:s'));
        //         $end = new DateTime($endDateNight->format('Y-m-d\TH:i:s'));
        //     } else {
        //         // Jika belum lewat jam 18:30, gunakan shift siang
        //         $start = new DateTime($startDateMorning->format('Y-m-d\TH:i:s'));
        //         $end = new DateTime($endDateMorning->format('Y-m-d\TH:i:s'));
        //     }
        // } else {
        //     // Jika parameter rangeStartVerif dan rangeEndVerif ada di URL, gunakan nilai tersebut
        //     $start = new DateTime($request->rangeStartVerif);
        //     $end = new DateTime($request->rangeEndVerif);
        // }

        // // Format waktu sesuai dengan format yang diinginkan
        // $startTimeFormatted = $start->format('Y-m-d H:i:s');
        // $endTimeFormatted = $end->format('Y-m-d H:i:s');

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

        // dd($endTimeFormatted);


        $loading = DB::table('klkh_loadingpoint_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lp.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'lp.id',
            'lp.uuid',
            'lp.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'lp.created_at as tanggal_pembuatan',
            'lp.time as jam_pelaporan',
            'lp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(lp.date) = 1 THEN rs.[1]
                    WHEN DAY(lp.date) = 2 THEN rs.[2]
                    WHEN DAY(lp.date) = 3 THEN rs.[3]
                    WHEN DAY(lp.date) = 4 THEN rs.[4]
                    WHEN DAY(lp.date) = 5 THEN rs.[5]
                    WHEN DAY(lp.date) = 6 THEN rs.[6]
                    WHEN DAY(lp.date) = 7 THEN rs.[7]
                    WHEN DAY(lp.date) = 8 THEN rs.[8]
                    WHEN DAY(lp.date) = 9 THEN rs.[9]
                    WHEN DAY(lp.date) = 10 THEN rs.[10]
                    WHEN DAY(lp.date) = 11 THEN rs.[11]
                    WHEN DAY(lp.date) = 12 THEN rs.[12]
                    WHEN DAY(lp.date) = 13 THEN rs.[13]
                    WHEN DAY(lp.date) = 14 THEN rs.[14]
                    WHEN DAY(lp.date) = 15 THEN rs.[15]
                    WHEN DAY(lp.date) = 16 THEN rs.[16]
                    WHEN DAY(lp.date) = 17 THEN rs.[17]
                    WHEN DAY(lp.date) = 18 THEN rs.[18]
                    WHEN DAY(lp.date) = 19 THEN rs.[19]
                    WHEN DAY(lp.date) = 20 THEN rs.[20]
                    WHEN DAY(lp.date) = 21 THEN rs.[21]
                    WHEN DAY(lp.date) = 22 THEN rs.[22]
                    WHEN DAY(lp.date) = 23 THEN rs.[23]
                    WHEN DAY(lp.date) = 24 THEN rs.[24]
                    WHEN DAY(lp.date) = 25 THEN rs.[25]
                    WHEN DAY(lp.date) = 26 THEN rs.[26]
                    WHEN DAY(lp.date) = 27 THEN rs.[27]
                    WHEN DAY(lp.date) = 28 THEN rs.[28]
                    WHEN DAY(lp.date) = 29 THEN rs.[29]
                    WHEN DAY(lp.date) = 30 THEN rs.[30]
                    WHEN DAY(lp.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'LOADING POINT' as source_table")
        )
        ->where('lp.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(lp.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(lp.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        // dd($loading->get());


        $haulroad = DB::table('klkh_haulroad_t as hr')
        ->leftJoin('users as us', 'hr.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'hr.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'hr.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'hr.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'hr.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'hr.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'hr.id',
            'hr.uuid',
            'hr.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'hr.created_at as tanggal_pembuatan',
            'hr.time as jam_pelaporan',
            'hr.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(hr.date) = 1 THEN rs.[1]
                    WHEN DAY(hr.date) = 2 THEN rs.[2]
                    WHEN DAY(hr.date) = 3 THEN rs.[3]
                    WHEN DAY(hr.date) = 4 THEN rs.[4]
                    WHEN DAY(hr.date) = 5 THEN rs.[5]
                    WHEN DAY(hr.date) = 6 THEN rs.[6]
                    WHEN DAY(hr.date) = 7 THEN rs.[7]
                    WHEN DAY(hr.date) = 8 THEN rs.[8]
                    WHEN DAY(hr.date) = 9 THEN rs.[9]
                    WHEN DAY(hr.date) = 10 THEN rs.[10]
                    WHEN DAY(hr.date) = 11 THEN rs.[11]
                    WHEN DAY(hr.date) = 12 THEN rs.[12]
                    WHEN DAY(hr.date) = 13 THEN rs.[13]
                    WHEN DAY(hr.date) = 14 THEN rs.[14]
                    WHEN DAY(hr.date) = 15 THEN rs.[15]
                    WHEN DAY(hr.date) = 16 THEN rs.[16]
                    WHEN DAY(hr.date) = 17 THEN rs.[17]
                    WHEN DAY(hr.date) = 18 THEN rs.[18]
                    WHEN DAY(hr.date) = 19 THEN rs.[19]
                    WHEN DAY(hr.date) = 20 THEN rs.[20]
                    WHEN DAY(hr.date) = 21 THEN rs.[21]
                    WHEN DAY(hr.date) = 22 THEN rs.[22]
                    WHEN DAY(hr.date) = 23 THEN rs.[23]
                    WHEN DAY(hr.date) = 24 THEN rs.[24]
                    WHEN DAY(hr.date) = 25 THEN rs.[25]
                    WHEN DAY(hr.date) = 26 THEN rs.[26]
                    WHEN DAY(hr.date) = 27 THEN rs.[27]
                    WHEN DAY(hr.date) = 28 THEN rs.[28]
                    WHEN DAY(hr.date) = 29 THEN rs.[29]
                    WHEN DAY(hr.date) = 30 THEN rs.[30]
                    WHEN DAY(hr.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'HAUL ROAD' as source_table")
        )
        ->where('hr.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(hr.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(hr.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, hr.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);


        $disposal = DB::table('klkh_disposal_t as dp')
        ->leftJoin('users as us', 'dp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'dp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'dp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dp.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'dp.id',
            'dp.uuid',
            'dp.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'dp.created_at as tanggal_pembuatan',
            'dp.time as jam_pelaporan',
            'dp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(dp.date) = 1 THEN rs.[1]
                    WHEN DAY(dp.date) = 2 THEN rs.[2]
                    WHEN DAY(dp.date) = 3 THEN rs.[3]
                    WHEN DAY(dp.date) = 4 THEN rs.[4]
                    WHEN DAY(dp.date) = 5 THEN rs.[5]
                    WHEN DAY(dp.date) = 6 THEN rs.[6]
                    WHEN DAY(dp.date) = 7 THEN rs.[7]
                    WHEN DAY(dp.date) = 8 THEN rs.[8]
                    WHEN DAY(dp.date) = 9 THEN rs.[9]
                    WHEN DAY(dp.date) = 10 THEN rs.[10]
                    WHEN DAY(dp.date) = 11 THEN rs.[11]
                    WHEN DAY(dp.date) = 12 THEN rs.[12]
                    WHEN DAY(dp.date) = 13 THEN rs.[13]
                    WHEN DAY(dp.date) = 14 THEN rs.[14]
                    WHEN DAY(dp.date) = 15 THEN rs.[15]
                    WHEN DAY(dp.date) = 16 THEN rs.[16]
                    WHEN DAY(dp.date) = 17 THEN rs.[17]
                    WHEN DAY(dp.date) = 18 THEN rs.[18]
                    WHEN DAY(dp.date) = 19 THEN rs.[19]
                    WHEN DAY(dp.date) = 20 THEN rs.[20]
                    WHEN DAY(dp.date) = 21 THEN rs.[21]
                    WHEN DAY(dp.date) = 22 THEN rs.[22]
                    WHEN DAY(dp.date) = 23 THEN rs.[23]
                    WHEN DAY(dp.date) = 24 THEN rs.[24]
                    WHEN DAY(dp.date) = 25 THEN rs.[25]
                    WHEN DAY(dp.date) = 26 THEN rs.[26]
                    WHEN DAY(dp.date) = 27 THEN rs.[27]
                    WHEN DAY(dp.date) = 28 THEN rs.[28]
                    WHEN DAY(dp.date) = 29 THEN rs.[29]
                    WHEN DAY(dp.date) = 30 THEN rs.[30]
                    WHEN DAY(dp.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'DISPOSAL/DUMPING POINT' as source_table")
        )
        ->where('dp.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(dp.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(dp.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, dp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        $lumpur = DB::table('klkh_lumpur_t as lum')
        ->leftJoin('users as us', 'lum.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lum.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lum.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lum.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lum.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lum.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'lum.id',
            'lum.uuid',
            'lum.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'lum.created_at as tanggal_pembuatan',
            'lum.time as jam_pelaporan',
            'lum.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(lum.date) = 1 THEN rs.[1]
                    WHEN DAY(lum.date) = 2 THEN rs.[2]
                    WHEN DAY(lum.date) = 3 THEN rs.[3]
                    WHEN DAY(lum.date) = 4 THEN rs.[4]
                    WHEN DAY(lum.date) = 5 THEN rs.[5]
                    WHEN DAY(lum.date) = 6 THEN rs.[6]
                    WHEN DAY(lum.date) = 7 THEN rs.[7]
                    WHEN DAY(lum.date) = 8 THEN rs.[8]
                    WHEN DAY(lum.date) = 9 THEN rs.[9]
                    WHEN DAY(lum.date) = 10 THEN rs.[10]
                    WHEN DAY(lum.date) = 11 THEN rs.[11]
                    WHEN DAY(lum.date) = 12 THEN rs.[12]
                    WHEN DAY(lum.date) = 13 THEN rs.[13]
                    WHEN DAY(lum.date) = 14 THEN rs.[14]
                    WHEN DAY(lum.date) = 15 THEN rs.[15]
                    WHEN DAY(lum.date) = 16 THEN rs.[16]
                    WHEN DAY(lum.date) = 17 THEN rs.[17]
                    WHEN DAY(lum.date) = 18 THEN rs.[18]
                    WHEN DAY(lum.date) = 19 THEN rs.[19]
                    WHEN DAY(lum.date) = 20 THEN rs.[20]
                    WHEN DAY(lum.date) = 21 THEN rs.[21]
                    WHEN DAY(lum.date) = 22 THEN rs.[22]
                    WHEN DAY(lum.date) = 23 THEN rs.[23]
                    WHEN DAY(lum.date) = 24 THEN rs.[24]
                    WHEN DAY(lum.date) = 25 THEN rs.[25]
                    WHEN DAY(lum.date) = 26 THEN rs.[26]
                    WHEN DAY(lum.date) = 27 THEN rs.[27]
                    WHEN DAY(lum.date) = 28 THEN rs.[28]
                    WHEN DAY(lum.date) = 29 THEN rs.[29]
                    WHEN DAY(lum.date) = 30 THEN rs.[30]
                    WHEN DAY(lum.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'DUMPING DIKOLAM AIR/LUMPUR' as source_table")
        )
        ->where('lum.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(lum.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(lum.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, lum.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        $ogs = DB::table('klkh_ogs_t as ogs')
        ->leftJoin('users as us', 'ogs.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'ogs.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'ogs.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'ogs.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'ogs.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'ogs.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'ogs.id',
            'ogs.uuid',
            'ogs.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'ogs.created_at as tanggal_pembuatan',
            'ogs.time as jam_pelaporan',
            'ogs.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(ogs.date) = 1 THEN rs.[1]
                    WHEN DAY(ogs.date) = 2 THEN rs.[2]
                    WHEN DAY(ogs.date) = 3 THEN rs.[3]
                    WHEN DAY(ogs.date) = 4 THEN rs.[4]
                    WHEN DAY(ogs.date) = 5 THEN rs.[5]
                    WHEN DAY(ogs.date) = 6 THEN rs.[6]
                    WHEN DAY(ogs.date) = 7 THEN rs.[7]
                    WHEN DAY(ogs.date) = 8 THEN rs.[8]
                    WHEN DAY(ogs.date) = 9 THEN rs.[9]
                    WHEN DAY(ogs.date) = 10 THEN rs.[10]
                    WHEN DAY(ogs.date) = 11 THEN rs.[11]
                    WHEN DAY(ogs.date) = 12 THEN rs.[12]
                    WHEN DAY(ogs.date) = 13 THEN rs.[13]
                    WHEN DAY(ogs.date) = 14 THEN rs.[14]
                    WHEN DAY(ogs.date) = 15 THEN rs.[15]
                    WHEN DAY(ogs.date) = 16 THEN rs.[16]
                    WHEN DAY(ogs.date) = 17 THEN rs.[17]
                    WHEN DAY(ogs.date) = 18 THEN rs.[18]
                    WHEN DAY(ogs.date) = 19 THEN rs.[19]
                    WHEN DAY(ogs.date) = 20 THEN rs.[20]
                    WHEN DAY(ogs.date) = 21 THEN rs.[21]
                    WHEN DAY(ogs.date) = 22 THEN rs.[22]
                    WHEN DAY(ogs.date) = 23 THEN rs.[23]
                    WHEN DAY(ogs.date) = 24 THEN rs.[24]
                    WHEN DAY(ogs.date) = 25 THEN rs.[25]
                    WHEN DAY(ogs.date) = 26 THEN rs.[26]
                    WHEN DAY(ogs.date) = 27 THEN rs.[27]
                    WHEN DAY(ogs.date) = 28 THEN rs.[28]
                    WHEN DAY(ogs.date) = 29 THEN rs.[29]
                    WHEN DAY(ogs.date) = 30 THEN rs.[30]
                    WHEN DAY(ogs.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'OGS' as source_table")
        )
        ->where('ogs.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(ogs.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(ogs.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, ogs.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        $batubara = DB::table('klkh_batubara_t as lp')
        ->leftJoin('users as us', 'lp.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'lp.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'lp.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'lp.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'lp.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'lp.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'lp.id',
            'lp.uuid',
            'lp.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'lp.created_at as tanggal_pembuatan',
            'lp.time as jam_pelaporan',
            'lp.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(lp.date) = 1 THEN rs.[1]
                    WHEN DAY(lp.date) = 2 THEN rs.[2]
                    WHEN DAY(lp.date) = 3 THEN rs.[3]
                    WHEN DAY(lp.date) = 4 THEN rs.[4]
                    WHEN DAY(lp.date) = 5 THEN rs.[5]
                    WHEN DAY(lp.date) = 6 THEN rs.[6]
                    WHEN DAY(lp.date) = 7 THEN rs.[7]
                    WHEN DAY(lp.date) = 8 THEN rs.[8]
                    WHEN DAY(lp.date) = 9 THEN rs.[9]
                    WHEN DAY(lp.date) = 10 THEN rs.[10]
                    WHEN DAY(lp.date) = 11 THEN rs.[11]
                    WHEN DAY(lp.date) = 12 THEN rs.[12]
                    WHEN DAY(lp.date) = 13 THEN rs.[13]
                    WHEN DAY(lp.date) = 14 THEN rs.[14]
                    WHEN DAY(lp.date) = 15 THEN rs.[15]
                    WHEN DAY(lp.date) = 16 THEN rs.[16]
                    WHEN DAY(lp.date) = 17 THEN rs.[17]
                    WHEN DAY(lp.date) = 18 THEN rs.[18]
                    WHEN DAY(lp.date) = 19 THEN rs.[19]
                    WHEN DAY(lp.date) = 20 THEN rs.[20]
                    WHEN DAY(lp.date) = 21 THEN rs.[21]
                    WHEN DAY(lp.date) = 22 THEN rs.[22]
                    WHEN DAY(lp.date) = 23 THEN rs.[23]
                    WHEN DAY(lp.date) = 24 THEN rs.[24]
                    WHEN DAY(lp.date) = 25 THEN rs.[25]
                    WHEN DAY(lp.date) = 26 THEN rs.[26]
                    WHEN DAY(lp.date) = 27 THEN rs.[27]
                    WHEN DAY(lp.date) = 28 THEN rs.[28]
                    WHEN DAY(lp.date) = 29 THEN rs.[29]
                    WHEN DAY(lp.date) = 30 THEN rs.[30]
                    WHEN DAY(lp.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'BATU BARA' as source_table")
        )
        ->where('lp.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(lp.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(lp.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, lp.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);


        $simpangempat = DB::table('klkh_simpangempat_t as se')
        ->leftJoin('users as us', 'se.pic', '=', 'us.id')
        ->leftJoin('REF_AREA as ar', 'se.pit_id', '=', 'ar.id')
        ->leftJoin('REF_SHIFT as sh', 'se.shift_id', '=', 'sh.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'se.foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'se.supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'se.superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'se.id',
            'se.uuid',
            'se.date as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'se.created_at as tanggal_pembuatan',
            'se.time as jam_pelaporan',
            'se.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'rs.unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(se.date) = 1 THEN rs.[1]
                    WHEN DAY(se.date) = 2 THEN rs.[2]
                    WHEN DAY(se.date) = 3 THEN rs.[3]
                    WHEN DAY(se.date) = 4 THEN rs.[4]
                    WHEN DAY(se.date) = 5 THEN rs.[5]
                    WHEN DAY(se.date) = 6 THEN rs.[6]
                    WHEN DAY(se.date) = 7 THEN rs.[7]
                    WHEN DAY(se.date) = 8 THEN rs.[8]
                    WHEN DAY(se.date) = 9 THEN rs.[9]
                    WHEN DAY(se.date) = 10 THEN rs.[10]
                    WHEN DAY(se.date) = 11 THEN rs.[11]
                    WHEN DAY(se.date) = 12 THEN rs.[12]
                    WHEN DAY(se.date) = 13 THEN rs.[13]
                    WHEN DAY(se.date) = 14 THEN rs.[14]
                    WHEN DAY(se.date) = 15 THEN rs.[15]
                    WHEN DAY(se.date) = 16 THEN rs.[16]
                    WHEN DAY(se.date) = 17 THEN rs.[17]
                    WHEN DAY(se.date) = 18 THEN rs.[18]
                    WHEN DAY(se.date) = 19 THEN rs.[19]
                    WHEN DAY(se.date) = 20 THEN rs.[20]
                    WHEN DAY(se.date) = 21 THEN rs.[21]
                    WHEN DAY(se.date) = 22 THEN rs.[22]
                    WHEN DAY(se.date) = 23 THEN rs.[23]
                    WHEN DAY(se.date) = 24 THEN rs.[24]
                    WHEN DAY(se.date) = 25 THEN rs.[25]
                    WHEN DAY(se.date) = 26 THEN rs.[26]
                    WHEN DAY(se.date) = 27 THEN rs.[27]
                    WHEN DAY(se.date) = 28 THEN rs.[28]
                    WHEN DAY(se.date) = 29 THEN rs.[29]
                    WHEN DAY(se.date) = 30 THEN rs.[30]
                    WHEN DAY(se.date) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan KLKH' as jenis_laporan"),
            DB::raw("'SIMPANG EMPAT' as source_table")
        )
        ->where('se.statusenabled', true)
        ->where('rs.tahun', DB::raw('YEAR(se.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(se.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, se.date, 120)'), [$startTimeFormatted, $endTimeFormatted]);


        $daily = DB::table('daily_report_t as dr')
        ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->leftJoin('REF_ROSTER_KERJA as rs', 'us.nik', '=', 'rs.nik')
        ->select(
            'dr.id',
            'dr.uuid',
            'dr.tanggal_dasar as tanggal_pelaporan',
            'us.name as pic',
            'us.nik as nik_pic',
            'dr.tanggal_dasar as tanggal_pembuatan',
            DB::raw('NULL as jam_pelaporan'),
            'dr.statusenabled',
            'ar.keterangan as pit',
            'sh.keterangan as shift',
            'lok.keterangan as unit_kerja',
            'us.role',
            DB::raw("
                CASE
                    WHEN DAY(dr.tanggal_dasar) = 1 THEN rs.[1]
                    WHEN DAY(dr.tanggal_dasar) = 2 THEN rs.[2]
                    WHEN DAY(dr.tanggal_dasar) = 3 THEN rs.[3]
                    WHEN DAY(dr.tanggal_dasar) = 4 THEN rs.[4]
                    WHEN DAY(dr.tanggal_dasar) = 5 THEN rs.[5]
                    WHEN DAY(dr.tanggal_dasar) = 6 THEN rs.[6]
                    WHEN DAY(dr.tanggal_dasar) = 7 THEN rs.[7]
                    WHEN DAY(dr.tanggal_dasar) = 8 THEN rs.[8]
                    WHEN DAY(dr.tanggal_dasar) = 9 THEN rs.[9]
                    WHEN DAY(dr.tanggal_dasar) = 10 THEN rs.[10]
                    WHEN DAY(dr.tanggal_dasar) = 11 THEN rs.[11]
                    WHEN DAY(dr.tanggal_dasar) = 12 THEN rs.[12]
                    WHEN DAY(dr.tanggal_dasar) = 13 THEN rs.[13]
                    WHEN DAY(dr.tanggal_dasar) = 14 THEN rs.[14]
                    WHEN DAY(dr.tanggal_dasar) = 15 THEN rs.[15]
                    WHEN DAY(dr.tanggal_dasar) = 16 THEN rs.[16]
                    WHEN DAY(dr.tanggal_dasar) = 17 THEN rs.[17]
                    WHEN DAY(dr.tanggal_dasar) = 18 THEN rs.[18]
                    WHEN DAY(dr.tanggal_dasar) = 19 THEN rs.[19]
                    WHEN DAY(dr.tanggal_dasar) = 20 THEN rs.[20]
                    WHEN DAY(dr.tanggal_dasar) = 21 THEN rs.[21]
                    WHEN DAY(dr.tanggal_dasar) = 22 THEN rs.[22]
                    WHEN DAY(dr.tanggal_dasar) = 23 THEN rs.[23]
                    WHEN DAY(dr.tanggal_dasar) = 24 THEN rs.[24]
                    WHEN DAY(dr.tanggal_dasar) = 25 THEN rs.[25]
                    WHEN DAY(dr.tanggal_dasar) = 26 THEN rs.[26]
                    WHEN DAY(dr.tanggal_dasar) = 27 THEN rs.[27]
                    WHEN DAY(dr.tanggal_dasar) = 28 THEN rs.[28]
                    WHEN DAY(dr.tanggal_dasar) = 29 THEN rs.[29]
                    WHEN DAY(dr.tanggal_dasar) = 30 THEN rs.[30]
                    WHEN DAY(dr.tanggal_dasar) = 31 THEN rs.[31]
                    ELSE NULL
                END as roster_kerja
            "),
            DB::raw("'Laporan Kerja' as jenis_laporan"),
            DB::raw("'Laporan Kerja' as source_table")
        )
        ->where('dr.statusenabled', true)
        ->where('dr.is_draft', false)
        ->where('rs.tahun', DB::raw('YEAR(dr.created_at)'))
        ->where('rs.bulan', DB::raw('MONTH(dr.created_at)'))
        ->whereBetween(DB::raw('CONVERT(varchar, dr.tanggal_dasar, 120)'), [$startTimeFormatted, $endTimeFormatted]);

        // dd($daily->get());

        //Gabung Table
        $combinedQuery = $loading->unionAll($haulroad)->unionAll($disposal)->unionAll($lumpur)
        ->unionAll($ogs)->unionAll($batubara)->unionAll($simpangempat)->unionAll($daily);


        // $combinedQuery = $combinedQuery->get()->groupBy('source_table');
        $combinedQuery = $combinedQuery->get();

        // dd($combinedQuery);

        session(['data_verified' => $combinedQuery]);


        return view('monitoring-lk-klkh.index', compact('combinedQuery'));
    }
}
