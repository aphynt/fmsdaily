<?php

namespace App\Http\Controllers;

use App\Models\StagingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    //
    public function index()
    {
        $nowHour = (int) date('H');

        if ($nowHour >= 7 && $nowHour <= 18) {

            // =========================
            // SHIFT SIANG
            // =========================
            $waktu = 'Siang';

            $shiftAktif   = 'Siang';
            $historyShift = 'Malam';

            $shiftAktifDate   = date('Y-m-d');
            $historyShiftDate = date('Y-m-d', strtotime('-1 day'));

        } else {

            // =========================
            // SHIFT MALAM
            // =========================
            $waktu = 'Malam';

            $shiftAktif   = 'Malam';
            $historyShift = 'Siang';

            if ($nowHour < 7) {
                $shiftAktifDate   = date('Y-m-d', strtotime('-1 day'));
                $historyShiftDate = date('Y-m-d', strtotime('-1 day'));
            } else {
                $shiftAktifDate   = date('Y-m-d');
                $historyShiftDate = date('Y-m-d');
            }
        }

        // =========================
        // DATA PER HOUR (tetap)
        // =========================
        $dataPerHour = DB::table('FOCUS_REPORTING.DASHBOARD.PRODUCTION_PER_HOUR as a')
            ->select([
                'PIT',
                'a.HOUR',
                'a.SORT',
                'PRODUCTION',
                DB::raw("COALESCE(
                    CASE
                        WHEN PLAN_PRODUCTION < 7000 AND PIT = 'ALL PIT' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-A3' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-B1' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-B2' THEN NULL
                        ELSE PLAN_PRODUCTION
                    END, PLAN_PRODUCTION) AS PLAN_PRODUCTION")
            ])
            ->where('PIT', 'ALL PIT')
            ->orderByRaw("
                CASE
                    WHEN a.HOUR >= 19 THEN a.HOUR
                    ELSE a.HOUR + 24
                END
            ")
            ->get();

        // =========================================================
        // STRATEGI PEMANGGILAN SP SESUAI POLA FINAL
        // =========================================================

        if ($shiftAktif === 'Siang') {

            // -------- CASE: SHIFT SIANG --------
            // Aktif   -> SP SIANG
            // History -> SP MALAM

            $rawAktif = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Siang']
            );

            $rawHistorySource = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

        } else {

            // -------- CASE: SHIFT MALAM --------
            // Aktif + History sudah ada di SP MALAM

            $rawAktif = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

            // history ambil dari hasil yang sama
            $rawHistorySource = $rawAktif;
        }

        // =========================================================
        // PISAHKAN DATA AKTIF & HISTORY
        // =========================================================
        $shiftNowRaw = array_values(array_filter($rawAktif, function ($row) use ($shiftAktif) {
            return $row->SHIFT_PROD === $shiftAktif;
        }));

        $shiftHistoryRaw = array_values(array_filter($rawHistorySource, function ($row) use ($historyShift) {
            return $row->SHIFT_PROD === $historyShift;
        }));

        // Kalau masih mau pakai filterByShiftHour, tetap aman
        $shiftNow     = $this->filterByShiftHour($shiftNowRaw, $shiftAktif);
        $shiftHistory = $this->filterByShiftHour($shiftHistoryRaw, $historyShift);

        // =========================================================
        // FINAL DATA
        // =========================================================
        $dataArray = $shiftNow;

        $actual = array_sum(array_map(fn($x) => (float) $x->PRODUCTION, $dataArray));
        // $plan   = array_sum(array_map(fn($x) => (float) $x->PLAN_PRODUCTION, $dataArray));
        $plan = DB::table('PLAN_PRODUCTION')->where('statusenabled', true)->sum('plan');

        $kategoriViewCompat = [
            'Siang'        => $waktu === 'Siang' ? $shiftNow : $shiftHistory,
            'Malam'        => $waktu === 'Malam' ? $shiftNow : $shiftHistory,
            'HistorySiang' => $waktu === 'Malam' ? $shiftHistory : [],
            'HistoryMalam' => $waktu === 'Siang' ? $shiftHistory : [],
        ];

        $data = [
            'kategori' => array_merge($kategoriViewCompat, [
                'ShiftAktif'       => $shiftNow,
                'HistoryShift'     => $shiftHistory,

            ]),
            'actual' => $actual,
            'plan'   => 50500,
            'waktu'  => $waktu,
        ];

        // dd($data);

        return view('production.index', compact('data'));
    }

    public function ex()
    {
        $nowHour = (int) date('H');

        if ($nowHour >= 7 && $nowHour <= 18) {

            // =========================
            // SHIFT SIANG
            // =========================
            $waktu = 'Siang';

            $shiftAktif   = 'Siang';
            $historyShift = 'Malam';

            $shiftAktifDate   = date('Y-m-d');
            $historyShiftDate = date('Y-m-d', strtotime('-1 day'));

        } else {

            // =========================
            // SHIFT MALAM
            // =========================
            $waktu = 'Malam';

            $shiftAktif   = 'Malam';
            $historyShift = 'Siang';

            if ($nowHour < 7) {
                $shiftAktifDate   = date('Y-m-d', strtotime('-1 day'));
                $historyShiftDate = date('Y-m-d', strtotime('-1 day'));
            } else {
                $shiftAktifDate   = date('Y-m-d');
                $historyShiftDate = date('Y-m-d');
            }
        }

        // =========================
        // DATA PER HOUR (tetap)
        // =========================
        $dataPerHour = DB::table('FOCUS_REPORTING.DASHBOARD.PRODUCTION_PER_HOUR as a')
            ->select([
                'PIT',
                'a.HOUR',
                'a.SORT',
                'PRODUCTION',
                DB::raw("COALESCE(
                    CASE
                        WHEN PLAN_PRODUCTION < 7000 AND PIT = 'ALL PIT' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-A3' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-B1' THEN NULL
                        WHEN PLAN_PRODUCTION < 2333.333 AND PIT = 'PIT SM-B2' THEN NULL
                        ELSE PLAN_PRODUCTION
                    END, PLAN_PRODUCTION) AS PLAN_PRODUCTION")
            ])
            ->where('PIT', 'ALL PIT')
            ->orderByRaw("
                CASE
                    WHEN a.HOUR >= 19 THEN a.HOUR
                    ELSE a.HOUR + 24
                END
            ")
            ->get();

        // =========================================================
        // STRATEGI PEMANGGILAN SP SESUAI POLA FINAL
        // =========================================================

        if ($shiftAktif === 'Siang') {

            // -------- CASE: SHIFT SIANG --------
            // Aktif   -> SP SIANG
            // History -> SP MALAM

            $rawAktif = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Siang']
            );

            $rawHistorySource = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

        } else {

            // -------- CASE: SHIFT MALAM --------
            // Aktif + History sudah ada di SP MALAM

            $rawAktif = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

            // history ambil dari hasil yang sama
            $rawHistorySource = $rawAktif;
        }

        // =========================================================
        // PISAHKAN DATA AKTIF & HISTORY
        // =========================================================
        $shiftNowRaw = array_values(array_filter($rawAktif, function ($row) use ($shiftAktif) {
            return $row->SHIFT_PROD === $shiftAktif;
        }));

        $shiftHistoryRaw = array_values(array_filter($rawHistorySource, function ($row) use ($historyShift) {
            return $row->SHIFT_PROD === $historyShift;
        }));

        // Kalau masih mau pakai filterByShiftHour, tetap aman
        $shiftNow     = $this->filterByShiftHour($shiftNowRaw, $shiftAktif);
        $shiftHistory = $this->filterByShiftHour($shiftHistoryRaw, $historyShift);

        // =========================================================
        // PER EX (LOGIKA SAMA DENGAN DI ATAS)
        // =========================================================

        if ($shiftAktif === 'Siang') {

            // Aktif -> SP SIANG
            $perExNow = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Siang']
            );

            // History -> SP MALAM
            $perExHistory = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

        } else {

            // Shift MALAM: cukup satu kali panggil
            $perExNow = DB::select(
                'SET NOCOUNT ON;
                EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
                ['Malam']
            );

            // History diambil dari hasil yang sama
            $perExHistory = $perExNow;
        }

        $nowHour = (int) date('H');
        $today   = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        if ($nowHour >= 7 && $nowHour < 19) {

            // =========================
            // SHIFT SIANG (07–19)
            // =========================
            $shiftIdAktif = 1;
            $startDateRef = $today;
            $endDateRef   = $today;

        } else {

            // =========================
            // SHIFT MALAM (19–07)
            // =========================
            $shiftIdAktif = 2;
            $startDateRef = $today;
            $endDateRef   = $tomorrow;
        }


        $staging = StagingPlan::where('statusenabled', true)
        ->where('shift_id', $shiftIdAktif)
        ->whereDate('start_date', '<=', $startDateRef)
        ->whereDate('end_date', '>=', $endDateRef)
        ->first();

        // =========================================================
        // GABUNG & OLAH PER EX (tetap seperti punya Anda)
        // =========================================================
        $perExSource = array_merge($perExNow, $perExHistory);
        $perExAll = collect($perExSource)
            ->filter(fn($r) => !empty($r->LOD_LOADERID))
            ->sortBy('LOD_LOADERID')
            ->where('PIT', '!=', NULL)
            ->values()
            ->toArray();

        $allEx = collect($perExAll)->pluck('LOD_LOADERID')->unique()->values();

        $hours = collect(range(0, 23))
            ->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT));

        $groupedPerExHour = [];

        foreach ($allEx as $ex) {
            foreach ($hours as $h) {
                $groupedPerExHour[$h . '_' . $ex] = [
                    'HOUR' => $h,
                    'LOD_LOADERID' => $ex,
                    'PRODUCTION' => 0,
                    'PLAN_PRODUCTION' => 0,
                    'ACH' => 0,
                ];
            }
        }

        foreach ($perExAll as $row) {
            if (!isset($row->HOUR, $row->LOD_LOADERID)) continue;

            $hour = str_pad($row->HOUR, 2, '0', STR_PAD_LEFT);
            $ex   = $row->LOD_LOADERID;

            $key = $hour . '_' . $ex;

            if (!isset($groupedPerExHour[$key])) continue;

            $groupedPerExHour[$key]['PRODUCTION'] += (float) $row->PRODUCTION;
            $groupedPerExHour[$key]['PLAN_PRODUCTION'] += (float) $row->PLAN_PRODUCTION;
        }

        foreach ($groupedPerExHour as &$g) {
            $g['ACH'] = $g['PLAN_PRODUCTION'] > 0
                ? ($g['PRODUCTION'] / $g['PLAN_PRODUCTION']) * 100
                : 0;
        }
        unset($g);

        $groupedPerExHourList = array_values($groupedPerExHour);

        if ($waktu === 'Malam') {
            usort($groupedPerExHourList, [$this, 'sortJamMalam']);
        }

        // =========================================================
        // FINAL DATA
        // =========================================================
        $dataArray = $shiftNow;
        $allForTotal = array_merge($shiftNow, $shiftHistory);

        $actual = collect($dataArray)->sum(fn($x) => (float) $x->PRODUCTION);
        // $plan   = collect($dataArray)->sum(fn($x) => (float) $x->PLAN_PRODUCTION);
        $plan = DB::table('PLAN_PRODUCTION')->where('statusenabled', true)->sum('plan');
        // $actual = array_sum(array_map(fn($x) => (float) $x->PRODUCTION, $dataArray));
        // $plan   = array_sum(array_map(fn($x) => (float) $x->PLAN_PRODUCTION, $dataArray));

        $kategoriViewCompat = [
            'Siang'        => $waktu === 'Siang' ? $shiftNow : $shiftHistory,
            'Malam'        => $waktu === 'Malam' ? $shiftNow : $shiftHistory,
            'HistorySiang' => $waktu === 'Malam' ? $shiftHistory : [],
            'HistoryMalam' => $waktu === 'Siang' ? $shiftHistory : [],
        ];

        $priorityExs = ['EX5279'];   // urutan = prioritas

        $orderedExIds = collect($perExNow)
            ->pluck('LOD_LOADERID')
            ->filter()
            ->map(fn($x) => trim((string)$x))
            ->unique()
            ->values()
            ->toArray();

        usort($orderedExIds, function ($a, $b) use ($priorityExs) {

            $pa = array_search($a, $priorityExs, true);
            $pb = array_search($b, $priorityExs, true);

            if ($pa !== false && $pb !== false) {
                return $pa <=> $pb;
            }

            // Jika hanya A priority → A di depan
            if ($pa !== false) return -1;

            // Jika hanya B priority → B di depan
            if ($pb !== false) return 1;

            // Kalau bukan priority → urut normal
            return strcmp($a, $b);
        });

        $data = [
            'kategori' => array_merge($kategoriViewCompat, [
                'ShiftAktif'       => $shiftNow,
                'HistoryShift'     => $shiftHistory,
                'PerExAktif'       => $perExNow,
                'PerExHistory'     => $perExHistory,
                'GroupedPerExHour' => $groupedPerExHourList,
                'OrderedExIds'     => $orderedExIds,
            ]),
            'actual' => $actual,
            'plan'   => $plan,
            'waktu'  => $waktu,
            'staging'  => $staging,
        ];

        // dd($data);

        return view('production.ex', compact('data'));
    }


    function sortJamMalam($a, $b)
    {
        $ha = (int)$a['HOUR'];
        $hb = (int)$b['HOUR'];

        // ubah ke "urut malam"
        $oa = ($ha >= 19) ? $ha : $ha + 24;
        $ob = ($hb >= 19) ? $hb : $hb + 24;

        return $oa <=> $ob;
    }

    private function filterByShiftHour(array $rows, string $shift): array
    {
        return array_values(array_filter($rows, function ($r) use ($shift) {

            $h = (int) $r->HOUR;

            // SIANG = 07–18
            if ($shift === 'Siang') {
                return $h >= 7 && $h <= 18;
            }

            // MALAM = 19–23 & 00–06
            if ($shift === 'Malam') {
                return ($h >= 19 || $h <= 6);
            }

            return true;
        }));
    }


}
