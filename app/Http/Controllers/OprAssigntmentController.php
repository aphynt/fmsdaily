<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OprAssigntmentController extends Controller
{
    //
    public function b1()
    {
        return view('opr-assignment.b1.index');
    }

    public function b1_api()
    {
        $data = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_SETTING_FLEET');

        $data = collect($data)->where('PIT', 'SM-B1')->groupBy('ASG_LOADERID')->map(function ($group) {
            return $group->sortBy('VHC_ID');
        });
        $html = view('opr-assignment.b1.partials', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

    public function b2()
    {
        return view('opr-assignment.b2.index');
    }

    public function b2_api()
    {
        $data = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_SETTING_FLEET');

        $data = collect($data)->where('PIT', 'SM-B2')->groupBy('ASG_LOADERID')->map(function ($group) {
            return $group->sortBy('VHC_ID');
        });
        $html = view('opr-assignment.b2.partials', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

    public function a3()
    {

        return view('opr-assignment.a3.index');
    }

    public function a3_api()
    {
        $data = DB::select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.RPT_REALTIME_SETTING_FLEET');

        $data = collect($data)->where('PIT', 'SM-A3')->groupBy('ASG_LOADERID')->map(function ($group) {
            return $group->sortBy('VHC_ID');
        });
        $html = view('opr-assignment.a3.partials', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

}
