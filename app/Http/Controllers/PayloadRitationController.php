<?php

namespace App\Http\Controllers;

use App\Models\PayloadRitation;
use App\Models\Ritation;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class PayloadRitationController extends Controller
{
    //
    public function index()
    {
        $data = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_PAYLOAD_RITATION');

        $data = collect($data)->map(function ($item) {
            return (object) array_map(function ($value) {
                // Cek jika nilai adalah angka, lalu bulatkan ke 1 angka di belakang koma
                return is_numeric($value) ? round($value, 1) : $value;
            }, (array) $item);
        });


        return view('payloadritation.index', compact('data'));
    }

    public function api()
    {
        $data = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_PAYLOAD_RITATION');

        $data = collect($data);

        // Return as JSON response
        return response()->json($data);

        return view('payloadritation.index', compact('data'));
    }

    public function exa()
    {
        $time = new DateTime();
        $startDate = $time->format('Y-m-d');
        $endDate = $time->format('Y-m-d');
        $waktuSekarang = Carbon::now();
        $jam = $waktuSekarang->hour;
        if ($jam >= 7 && $jam < 18) {
            $shift = 6;
        } else {
            $shift = 7;
        }
        $dataexa = DB::table('focus.dbo.PRD_LOADLIST')
        ->select([
            'OPR_REPORTTIME',
            'VHC_ID',
            'LOD_LOADERID',
            'LOC_NAME',
            'OPR_NAME',
            'OPR_SHIFTNO',
            'LOD_MAT_ID',
            DB::raw('ROUND(LOD_TONNAGE, 2) as LOD_TONNAGE'),
            DB::raw('ROUND(LOD_VOLUME, 2) as LOD_VOLUME'),
            'NET_IPADDRESS',
        ])
        ->whereBetween('OPR_SHIFTDATE', [$startDate, $endDate])
        ->where('OPR_SHIFTNO', $shift)
        ->orderBy('OPR_REPORTTIME')
        ->get();
    $data = $dataexa->groupBy('LOD_LOADERID');



        return view('payloadritation.exa', compact('data'));
    }

    public function exa_new()
    {

        $time = new DateTime();

        $waktuSekarang = Carbon::now();
        $jam = $waktuSekarang->hour;
        if ($jam >= 7 && $jam < 18) {
            // Shift 6 jika jam antara 7 dan 18
            $shift = 6;
            $startDate = $time->format('Y-m-d');  // Start date hari ini
            $endDate = $time->format('Y-m-d');    // End date hari ini
        } else {
            // Shift 7 jika jam di luar 7 hingga 18
            $shift = 7;
            $startDate = $time->format('Y-m-d');  // Start date hari ini
            // End date menjadi tanggal besok
            $endDate = $time->modify('+1 day')->format('Y-m-d');
        }

        $data = collect(DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_PAYLOAD_RITATION_NEW'));

        $data = $data->filter(function ($item) {
            return !empty($item->ASG_LOADERID);
        });

        // dd($data);

        $grouped = $data->groupBy('ASG_LOADERID')->sortBy('ASG_LOADERID')->map(function ($group) {
            return $group->reduce(function ($carry, $item) use ($group) {
                $groupCount = $group->count();

                foreach ($item as $key => $value) {
                    $averageFields = [
                        'PAYLOAD_LASTHOUR',
                        'PAYLOAD_SHIFT',
                    ];

                    if (in_array($key, $averageFields) && is_numeric($value)) {
                        $carry[$key] = isset($carry[$key])
                            ? (($carry[$key] * ($groupCount - 1) + $value) / $groupCount)
                            : $value;
                    } elseif (is_numeric($value)) {
                        $carry[$key] = isset($carry[$key]) ? $carry[$key] + $value : $value;
                    } else {
                        $carry[$key] = $carry[$key] ?? $value;
                    }
                }
                return $carry;
            });
        });


        return view('payloadritation.exa_new', compact('grouped'));
    }
}
