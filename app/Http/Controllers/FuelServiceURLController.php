<?php

namespace App\Http\Controllers;

use App\Models\FueJournal;
use App\Models\FuelServiceURL;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FuelServiceURLController extends Controller
{
    //
    public function serviceURL(Request $request, $token)
    {


        try {
            $data = FuelServiceURL::where('IS_ACTIVE', true)->where('TOKEN', $token)->first();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);

    }

    public function operator()
    {

        try {
            $data = DB::table('focus.dbo.FUE_OPERATOR')
        ->select(
            'OPR_NRP',
            'OPR_NAME',
            )
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function location()
    {
        try {
            $data = DB::table('focus.dbo.FUE_OPERATOR')
        ->select(
            'OPR_NRP',
            'OPR_NAME',
            )
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function shift()
    {
        try {
            $data = DB::table('focus.dbo.FLT_SHIFT')
        ->select(
            'SHIFTNO as ID',
            'SHIFTDESC',
            )
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);

    }

    public function type()
    {
        try {
            $data = DB::table('focus.dbo.FUE_TRANSTYPE')
        ->select(
            'TRANSTYPE',
            'TRANSTYPEDESC',
            )
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function unit()
    {
        try {
            $data = DB::table('focus.dbo.FUE_OBJECT')
        ->select(
            'OBJECTID',
            'OBJECTNAME',
            )
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function transfrom()
    {
        try {
            $data = DB::table('focus.dbo.FUE_OBJECTTYPE as objecttype')
        ->leftJoin('focus.dbo.FUE_OBJECT as object', 'objecttype.OBJECTTYPEID', 'object.OBJECTTYPEID')
        ->select(
            'objecttype.TRANSGROUPFROM',
            'object.OBJECTNAME',
            )
        ->where('objecttype.TRANSGROUPFROM', '!=', null)
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);

    }

    public function transto()
    {
        try {
            $data = DB::table('focus.dbo.FUE_OBJECTTYPE as objecttype')
        ->leftJoin('focus.dbo.FUE_OBJECT as object', 'objecttype.OBJECTTYPEID', 'object.OBJECTTYPEID')
        ->select(
            'objecttype.TRANSGROUPTO',
            'object.OBJECTNAME',
            )
        ->where('objecttype.TRANSGROUPTO', '!=', null)
        ->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function getDataFuelIncoming(Request $request)
    {
        try {
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


            $data = FueJournal::whereBetween(DB::raw('CONVERT(varchar, TRANSTIMESTAMP, 23)'), [$startTimeFormatted, $endTimeFormatted])->where('TRANSTYPE', 1)->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function getDataFuelOutgoing(Request $request)
    {
        try {
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


            $data = FueJournal::whereBetween(DB::raw('CONVERT(varchar, TRANSTIMESTAMP, 23)'), [$startTimeFormatted, $endTimeFormatted])->where('TRANSTYPE', 2)->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function getDataFuelTransfer(Request $request)
    {
        try {
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


            $data = FueJournal::whereBetween(DB::raw('CONVERT(varchar, TRANSTIMESTAMP, 23)'), [$startTimeFormatted, $endTimeFormatted])->where('TRANSTYPE', 3)->get();

            if($data){
                $transStatus = true;
                $transMessage = "Success";
            }else{
                $transStatus = true;
                $transMessage = "Token tidak ditemukam";
            }


        } catch (\Throwable $th) {
            $transStatus = false;
            $transMessage = $th->getMessage();
        }

        if ($transStatus != false) {
            $result = array(
                "data" => $data,
                "status" => 201,
                "message" => $transMessage,
            );
        } else {
            $result = array(
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return response()->json($result, $result['status']);
    }

    public function sendPostFuelIncoming(Request $request)
    {

        if (!$request->isJson()) {
            $result = array(
                "status" => 400,
                "message" => "Format data harus JSON",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Access-Token')) {
            $result = array(
                "status" => 400,
                "message" => "Token tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Network-Type')) {
            $result = array(
                "status" => 400,
                "message" => "Netwrk Type tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        $checkHeader = FuelServiceURL::where('TOKEN', $request->header('Access-Token'))
        ->where('TYPE', $request->header('Network-Type'))
        ->where('IS_ACTIVE', true)
        ->first();


        if (!$checkHeader) {
            $result = array(
                "status" => 400,
                "message" => "Service URL tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }


            try {
                $validated = $request->validate([
                    '*' => 'required|array',
                    '*.TRANSGROUPFROM' => 'required|integer',
                    '*.TRANSGROUPTO' => 'required|integer',
                    '*.VOLUME' => 'required|numeric',
                    '*.TRANSDESC' => 'nullable|string|max:100',
                    '*.TRANSREF' => 'nullable|string|max:40',
                    '*.MEMO' => 'nullable|string',
                    '*.TRANSFROM' => 'nullable|string|max:20',
                    '*.TRANSTO' => 'nullable|string|max:20',
                    '*.HOURMETER' => 'nullable|numeric',
                    '*.FLOWMETEREND' => 'nullable|numeric',
                    '*.TRANSTIMESTART' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSTIMEEND' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSSHIFT' => 'nullable|integer',
                    '*.TRANSTIMESTAMP' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSID' => 'nullable|string|max:26',
                    '*.TRANSUSERNAME' => 'nullable|string|max:16',
                ]);
                $journals = [];
                $maxJournalID = FueJournal::max('JOURNALID') + 1;

                foreach ($validated as $journalData) {
                    $journalData['TRANSTYPE'] = 1;
                    $journalData['JOURNALID'] = $maxJournalID++;
                    $journalData['TRANSDATE'] = now()->toDateString();

                    $journals[] = FueJournal::create($journalData);
                }

                $result = array(
                    "data" => $journals,
                    "status" => 201,
                    "message" => "Data berhasil disimpan",
                );
                return response()->json($result, $result['status']);

            } catch (\Exception $e) {
                $result = array(
                    "status" => 500,
                    "message" => "Terjadi kesalahan saat menyimpan data",
                );
                return response()->json($result, $result['status']);
            }

    }

    public function sendPostFuelOutgoing(Request $request)
    {

        if (!$request->isJson()) {
            $result = array(
                "status" => 400,
                "message" => "Format data harus JSON",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Access-Token')) {
            $result = array(
                "status" => 400,
                "message" => "Token tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Network-Type')) {
            $result = array(
                "status" => 400,
                "message" => "Netwrk Type tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        $checkHeader = FuelServiceURL::where('TOKEN', $request->header('Access-Token'))
        ->where('TYPE', $request->header('Network-Type'))
        ->where('IS_ACTIVE', true)
        ->first();


        if (!$checkHeader) {
            $result = array(
                "status" => 400,
                "message" => "Service URL tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }


            try {
                $validated = $request->validate([
                    '*' => 'required|array',
                    '*.TRANSGROUPFROM' => 'required|integer',
                    '*.TRANSGROUPTO' => 'required|integer',
                    '*.VOLUME' => 'required|numeric',
                    '*.TRANSDESC' => 'nullable|string|max:100',
                    '*.TRANSREF' => 'nullable|string|max:40',
                    '*.MEMO' => 'nullable|string',
                    '*.TRANSFROM' => 'nullable|string|max:20',
                    '*.TRANSTO' => 'nullable|string|max:20',
                    '*.HOURMETER' => 'nullable|numeric',
                    '*.FLOWMETEREND' => 'nullable|numeric',
                    '*.TRANSTIMESTART' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSTIMEEND' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSSHIFT' => 'nullable|integer',
                    '*.TRANSTIMESTAMP' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSID' => 'nullable|string|max:26',
                    '*.TRANSUSERNAME' => 'nullable|string|max:16',
                ]);
                $journals = [];
                $maxJournalID = FueJournal::max('JOURNALID') + 1;

                foreach ($validated as $journalData) {
                    $journalData['TRANSTYPE'] = 2;
                    $journalData['JOURNALID'] = $maxJournalID++;
                    $journalData['TRANSDATE'] = now()->toDateString();

                    $journals[] = FueJournal::create($journalData);
                }

                $result = array(
                    "data" => $journals,
                    "status" => 201,
                    "message" => "Data berhasil disimpan",
                );
                return response()->json($result, $result['status']);

            } catch (\Exception $e) {
                $result = array(
                    "status" => 500,
                    "message" => "Terjadi kesalahan saat menyimpan data",
                );
                return response()->json($result, $result['status']);
            }

    }

    public function sendPostFuelTransfer(Request $request)
    {

        if (!$request->isJson()) {
            $result = array(
                "status" => 400,
                "message" => "Format data harus JSON",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Access-Token')) {
            $result = array(
                "status" => 400,
                "message" => "Token tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        if (!$request->header('Network-Type')) {
            $result = array(
                "status" => 400,
                "message" => "Netwrk Type tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }

        $checkHeader = FuelServiceURL::where('TOKEN', $request->header('Access-Token'))
        ->where('TYPE', $request->header('Network-Type'))
        ->where('IS_ACTIVE', true)
        ->first();


        if (!$checkHeader) {
            $result = array(
                "status" => 400,
                "message" => "Service URL tidak ditemukan",
            );
            return response()->json($result, $result['status']);
        }


            try {
                $validated = $request->validate([
                    '*' => 'required|array',
                    '*.TRANSGROUPFROM' => 'required|integer',
                    '*.TRANSGROUPTO' => 'required|integer',
                    '*.VOLUME' => 'required|numeric',
                    '*.TRANSDESC' => 'nullable|string|max:100',
                    '*.TRANSREF' => 'nullable|string|max:40',
                    '*.MEMO' => 'nullable|string',
                    '*.TRANSFROM' => 'nullable|string|max:20',
                    '*.TRANSTO' => 'nullable|string|max:20',
                    '*.HOURMETER' => 'nullable|numeric',
                    '*.FLOWMETEREND' => 'nullable|numeric',
                    '*.TRANSTIMESTART' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSTIMEEND' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSSHIFT' => 'nullable|integer',
                    '*.TRANSTIMESTAMP' => 'nullable|date_format:Y-m-d H:i:s',
                    '*.TRANSID' => 'nullable|string|max:26',
                    '*.TRANSUSERNAME' => 'nullable|string|max:16',
                ]);
                $journals = [];
                $maxJournalID = FueJournal::max('JOURNALID') + 1;

                foreach ($validated as $journalData) {
                    $journalData['TRANSTYPE'] = 3;
                    $journalData['JOURNALID'] = $maxJournalID++;
                    $journalData['TRANSDATE'] = now()->toDateString();

                    $journals[] = FueJournal::create($journalData);
                }

                $result = array(
                    "data" => $journals,
                    "status" => 201,
                    "message" => "Data berhasil disimpan",
                );
                return response()->json($result, $result['status']);

            } catch (\Exception $e) {
                $result = array(
                    "status" => 500,
                    "message" => "Terjadi kesalahan saat menyimpan data",
                );
                return response()->json($result, $result['status']);
            }

    }
}
