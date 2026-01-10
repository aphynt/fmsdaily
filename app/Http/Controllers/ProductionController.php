<?php

namespace App\Http\Controllers;

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

            $waktu = 'Siang';

            $shiftAktif   = 'Siang';
            $historyShift = 'Malam';

            $shiftAktifDate   = date('Y-m-d');
            $historyShiftDate = date('Y-m-d', strtotime('-1 day'));
        } else {

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

        $shiftNowRaw = DB::select(
            'SET NOCOUNT ON;
            EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
            @shift = ?',
            [$shiftAktif]
        );

        $shiftHistoryRaw = DB::select(
            'SET NOCOUNT ON;
            EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_TODAY_AND_LAST_SHIFT
            @shift = ?',
            [$historyShift]
        );

        $shiftNow     = $this->filterByShiftHour($shiftNowRaw, $shiftAktif);
        $shiftHistory = $this->filterByShiftHour($shiftHistoryRaw, $historyShift);


        $perExNow = DB::select(
            'SET NOCOUNT ON;
            EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
            [$shiftAktif]
        );

        $perExHistory = DB::select(
            'SET NOCOUNT ON;
            EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
            [$historyShift]
        );

        $perExAll = DB::select(
            'SET NOCOUNT ON;
            EXEC FOCUS_REPORTING.dbo.APP_GET_PRODUCTION_PER_EX_TODAY_AND_LAST_SHIFT
                @shift = ?',
            ['']
        );

        $perExSource = array_merge($perExNow, $perExHistory);
        $perExAll = collect($perExSource)
            ->filter(fn($r) => !empty($r->LOD_LOADERID))
            ->sortBy('LOD_LOADERID')
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

        // overlay data real
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

        // SORT KHUSUS UNTUK MODE MALAM
        if ($waktu === 'Malam') {
            usort($groupedPerExHourList, [$this, 'sortJamMalam']);
        }

        // if ($waktu === 'Siang') {

        //     $dataArray = array_merge($shiftNow, $shiftHistory);
        // } else {

        //     $dataArray = array_merge($shiftNow, $shiftHistory);
        // }

        $dataArray = $shiftNow;

        $actual = array_sum(array_map(fn($x) => (float) $x->PRODUCTION, $dataArray));
        $plan   = array_sum(array_map(fn($x) => (float) $x->PLAN_PRODUCTION, $dataArray));

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
            'PerExAktif'       => $perExNow,
            'PerExHistory'     => $perExHistory,
            'GroupedPerExHour' => $groupedPerExHourList,
        ]),
        'actual' => $actual,
        'plan'   => $plan,
        'waktu'  => $waktu,
    ];

        return view('production.index', compact('data'));
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
