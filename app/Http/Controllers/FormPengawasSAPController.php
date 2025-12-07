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
            $fileUrl = url('storage/' . $filePath);
            SAPReportImage::create([
                'uuid' => (string) Uuid::uuid4()->toString(),
                'report_uuid' => $reportUuid,
                'path' => $fileUrl,
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

        DB::beginTransaction();

        try {
            $fileTemuan = null;
            $fileTindakLanjut = null;

            if ($request->hasFile('file_temuan')) {
                $file = $request->file('file_temuan');
                $destinationPath = public_path('storage/sap/file_temuan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);
                $fileTemuan = url('storage/sap/file_temuan/' . $fileName);
            }

            if ($request->hasFile('file_tindakLanjut')) {
                $file2 = $request->file('file_tindakLanjut');
                $destinationPath2 = public_path('storage/sap/file_tindakLanjut');
                $fileName2 = time() . '_' . $file2->getClientOriginalName();
                $file2->move($destinationPath2, $fileName2);
                $fileTindakLanjut = url('storage/sap/file_tindakLanjut/' . $fileName2);
            }

            $report = SAPReport::create([
                'uuid'           => (string) Uuid::uuid4()->toString(),
                'foreman_id'     => Auth::user()->id,
                'shift'          => $request->shift,
                'area'           => $request->area,
                'jam_kejadian'   => $request->jamKejadian,
                'temuan'         => $request->temuan,
                'tingkat_risiko'  => $request->tingkatRisiko,
                'tindak_lanjut'  => $request->tindakLanjut,
                'risiko'         => $request->risiko,
                'pengendalian'   => $request->pengendalian,
                'file_temuan'    => $fileTemuan,
                'file_tindakLanjut' => $fileTindakLanjut,
                'is_finish'      => false,
            ]);

            DB::commit();

            // return response()->json([
            //     'status'  => 'success',
            //     'message' => 'Report berhasil diposting',
            //     'data'    => $report,
            // ]);

            return redirect()->route('form-pengawas-sap.show')->with('success', 'SAP berhasil diposting');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('info', 'SAP gagal diposting'. $th->getMessage());

            // return response()->json([
            //     'status'  => 'error',
            //     'message' => $th->getMessage(),
            // ], 500);
        }
    }


    public function update(Request $request, $uuid)
    {

        // return $request;
        DB::beginTransaction();

        try {

            $report = SAPReport::where('uuid', $uuid)->firstOrFail();

            $fileTemuan       = $report->file_temuan;
            $fileTindakLanjut = $report->file_tindakLanjut;

            // === HANDLE FILE TEMUAN ===
            if ($request->hasFile('file_temuan')) {
                $file = $request->file('file_temuan');
                $destinationPath = public_path('storage/sap/file_temuan');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $fileName);

                $fileTemuan = url('storage/sap/file_temuan/' . $fileName);
            }

            // === HANDLE FILE TINDAK LANJUT ===
            if ($request->hasFile('file_tindakLanjut')) {
                $file2 = $request->file('file_tindakLanjut');
                $destinationPath2 = public_path('storage/sap/file_tindakLanjut');

                if (!file_exists($destinationPath2)) {
                    mkdir($destinationPath2, 0755, true);
                }

                $fileName2 = time() . '_' . $file2->getClientOriginalName();
                $file2->move($destinationPath2, $fileName2);

                $fileTindakLanjut = url('storage/sap/file_tindakLanjut/' . $fileName2);
            }


            $dataUpdate = [
                'temuan'           => $request->temuan,
                'tindak_lanjut'    => $request->tindakLanjut,
                'risiko'           => $request->risiko,
                'tingkat_risiko'   => $request->tingkatRisiko,
                'pengendalian'     => $request->pengendalian,
                'file_temuan'      => $fileTemuan,
                'file_tindakLanjut'=> $fileTindakLanjut,
                'is_finish'        => true,
            ];

            $report->update($dataUpdate);

            DB::commit();

            // return response()->json([
            //     'status'  => 'success',
            //     'message' => 'Report berhasil diupdate!',
            //     'data'    => $report,
            // ]);

            return redirect()->route('form-pengawas-sap.show')->with('success', 'SAP berhasil diclosing');
        }  catch (\Throwable $e) {
            DB::rollBack();

            // return response()->json([
            //     'status'  => 'error',
            //     'message' => 'Report gagal diupdate: ' . $e->getMessage(),
            // ], 500);
            return redirect()->back()->with('info', 'SAP gagal diposting'. $e->getMessage());
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
            'sr.tingkat_risiko',
            'sr.tindak_lanjut',
            'sr.pengendalian',
            'sr.file_temuan',
            'sr.file_tindakLanjut',
            'sr.is_finish'
        )
        ->where('sr.statusenabled', true)
        ->where('sr.uuid', $uuid)->first();

        if($report == null){
            return redirect()->back()->with('info', 'Maaf, SAP tidak ditemukan');
        }

        $data = [
            'report' => $report,
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
