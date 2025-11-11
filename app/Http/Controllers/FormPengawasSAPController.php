<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\SAPReport;
use App\Models\SAPReportImage;
use App\Models\Shift;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class FormPengawasSAPController extends Controller
{

    private function handleFileUpload($files, $reportUuid, $folder, $type)
    {
        foreach ($files as $file) {
            $filePath = $file->store($folder, 'public');
            SAPReportImage::create([
                'uuid' => (string) Uuid::uuid4()->toString(),
                'report_uuid' => $reportUuid,
                'path' => $filePath,
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'format' => $file->extension(),
                'type' => $type,
            ]);
        }
    }

    //
    public function index()
    {
        $shift = Shift::where('statusenabled', true)->get();
        $area = Area::where('statusenabled', true)->get();
        return view('form-sap.index', compact('area', 'shift'));
    }

    public function post(Request $request)
    {

        // dd($request->all());

        // $request->validate([
        //     'jamKejadian' => 'nullable|date_format:H:i',
        //     'shift' => 'nullable|string',
        //     'area' => 'nullable|string',
        //     'temuan' => 'nullable|string',
        //     'tindakLanjut' => 'nullable|string',
        //     'risiko' => 'nullable|string',
        //     'pengendalian' => 'nullable|string',
        //     'file_temuan.*' => 'nullable|file|mimes:jpeg,png,gif,jpg,bmp,webp|max:20000',
        //     'file_tindakLanjut.*' => 'nullable|file|mimes:jpeg,png,gif,jpg,bmp,webp|max:20000'
        // ]);

        // Menyimpan data Report
        try {
            $report = SAPReport::create([
                'uuid' => (string) Uuid::uuid4()->toString(),
                'foreman_id' => Auth::user()->id,
                'shift' => $request->shift,
                'area' => $request->area,
                'jam_kejadian' => $request->jamKejadian,
                'temuan' => $request->temuan,
                'tindak_lanjut' => $request->tindakLanjut,
                'risiko' => $request->risiko,
                'pengendalian' => $request->pengendalian,
                'is_finish' => false,
            ]);


            if ($request->hasFile('file_temuan')) {
                $this->handleFileUpload($request->file('file_temuan'), $report->uuid, 'sap/file_temuan', 'TEMUAN');
            }

            if ($request->hasFile('file_tindakLanjut')) {
                $this->handleFileUpload($request->file('file_tindakLanjut'), $report->uuid, 'sap/file_tindakLanjut', 'TINDAK LANJUT');
            }

            return response()->json(['message' => 'Report berhasil diposting!']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Report gagal diposting'. $th->getMessage()], 500);
        }


    }

    public function update(Request $request, $uuid)
    {
        // return $request->all();
        try {
            $report = SAPReport::where('uuid', $uuid)->first();

            $report->update([

                'temuan' => $request->temuan,
                'tindak_lanjut' => $request->tindakLanjut,
                'risiko' => $request->risiko,
                'pengendalian' => $request->pengendalian,
                'is_finish' => true,  // Jika ingin tetap false saat update
            ]);

            // Cek jika ada file yang diupload dan menangani upload file
            if ($request->hasFile('file_temuan')) {
                $this->handleFileUpload($request->file('file_temuan'), $report->uuid, 'sap/file_temuan', 'TEMUAN');
            }

            if ($request->hasFile('file_tindakLanjut')) {
                $this->handleFileUpload($request->file('file_tindakLanjut'), $report->uuid, 'sap/file_tindakLanjut', 'TINDAK LANJUT');
            }

            return response()->json(['message' => 'Report berhasil diupdate!']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Report gagal diupdate'. $th->getMessage()], 500);
        }
    }

    public function rincian($uuid)
    {
        $report = DB::table('SAP_REPORT as sr')
        ->leftJoin('users as us', 'sr.foreman_id', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'sr.shift', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'sr.area', 'ar.id')
        ->select(
            'sr.uuid',
            'sr.created_at',
            'sr.jam_kejadian',
            'sh.keterangan as shift',
            'us.nik as nik_pic',
            'us.name as pic',
            'ar.keterangan as area',
            'sr.temuan',
            'sr.risiko',
            'sr.tindak_lanjut',
            'sr.pengendalian',
            'sr.is_finish'
        )
        ->where('sr.statusenabled', true)
        ->where('sr.uuid', $uuid)->first();

        $imageTemuan = DB::table('SAP_REPORT_IMAGE as im')
        ->leftJoin('SAP_REPORT as sr', 'im.report_uuid', 'sr.uuid')
        ->where('im.statusenabled', true)
        ->where('report_uuid', $uuid)->where('type', 'TEMUAN')->get();

        $imageTindakLanjut = DB::table('SAP_REPORT_IMAGE as im')
        ->leftJoin('SAP_REPORT as sr', 'im.report_uuid', 'sr.uuid')
        ->where('im.statusenabled', true)
        ->where('report_uuid', $uuid)->where('type', 'TINDAK LANJUT')->get();

        $data = [
            'report' => $report,
            'imageTemuan' => $imageTemuan,
            'imageTindakLanjut' => $imageTindakLanjut,
        ];

        // dd($data);

        if($report->is_finish == true){
            return view('form-sap.show', compact('data'));
        }else{
            return view('form-sap.update', compact('data'));
        }
    }

    public function delete($uuid)
    {
        try {
            SAPReport::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            SAPReportImage::where('report_uuid', $uuid)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Laporan SAP berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', $th->getMessage());
        }
    }

    public function show(Request $request)
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

        $report = DB::table('SAP_REPORT as sr')
        ->leftJoin('users as us', 'sr.foreman_id', 'us.id')
        ->leftJoin('REF_SHIFT as sh', 'sr.shift', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'sr.area', 'ar.id')
        ->select(
            'sr.uuid',
            'sr.created_at',
            'sr.jam_kejadian',
            'sh.keterangan as shift',
            'us.nik as nik_pic',
            'us.name as pic',
            'ar.keterangan as area',
            'sr.temuan',
            'sr.risiko',
            'sr.pengendalian',
            'sr.tindak_lanjut',
            'sr.is_finish',
        )
        ->whereBetween(DB::raw('CONVERT(varchar, sr.created_at, 23)'), [$startTimeFormatted, $endTimeFormatted])
        ->where('sr.statusenabled', true);
        $report = $report->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('sr.foreman_id', Auth::user()->id);
            }
        });
        $report = $report->orderBy('created_at', 'DESC')->get();

        return view('form-sap.daftar.index', compact('report'));
    }

}
