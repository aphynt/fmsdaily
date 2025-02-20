<?php

namespace App\Http\Controllers;

use App\Models\FrontLoading;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FrontLoadingController extends Controller
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

        $front = DB::table('front_loading_t as fl')
        ->leftJoin('daily_report_t as dr', 'fl.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        // ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'fl.daily_report_id as id',
            'dr.nik_foreman',
            'gl.PERSONALNAME as nama_foreman',
            'dr.tanggal_dasar as tanggal_pelaporan',
            'sh.keterangan as shift',
            'ar.keterangan as area',
            'lok.keterangan as lokasi',
            'dr.nik_supervisor as nik_supervisor',
            'spv.PERSONALNAME as nama_supervisor',
            'dr.nik_superintendent as nik_superintendent',
            'spt.PERSONALNAME as nama_superintendent',
            'fl.nomor_unit',
            'fl.siang',
            'fl.malam',
            'fl.keterangan',
            'dr.created_at',
            'dr.updated_at',
        )
        ->whereNotNull('nomor_unit')
        ->where('dr.statusenabled', true)
        ->where('fl.statusenabled', true)
        ->whereBetween('tanggal_dasar', [$startTimeFormatted, $endTimeFormatted]);
        if (Auth::user()->role !== 'ADMIN') {
            $front->where('dr.foreman_id', Auth::user()->id);
        }

        $front = $front->get()
    ->flatMap(function ($item) {
        $siang = json_decode($item->siang, true) ?? [];
        $malam = json_decode($item->malam, true) ?? [];
        $ket = json_decode($item->keterangan, true) ?? [];

        $result = [];
        $siangKeteranganIndex = 0; // Indeks keterangan untuk siang
        $malamKeteranganIndex = 0; // Indeks keterangan untuk malam

        // Proses shift Malam
        if ($item->shift !== 'Siang') {
            foreach ($malam as $waktu) {
                $keteranganMalam = $ket[$malamKeteranganIndex] ?? '';  // Ambil keterangan untuk shift Malam
                $result[] = [
                    'id' => $item->id,
                    'tanggal_pelaporan' => $item->tanggal_pelaporan,
                    'shift' => $item->shift,
                    'area' => $item->area,
                    'lokasi' => $item->lokasi,
                    'jam' => $waktu,
                    'nomor_unit' => $item->nomor_unit,
                    'nik_foreman' => $item->nik_foreman,
                    'nama_foreman' => $item->nama_foreman,
                    'nik_supervisor' => $item->nik_supervisor,
                    'nama_supervisor' => $item->nama_supervisor,
                    'nik_superintendent' => $item->nik_superintendent,
                    'nama_superintendent' => $item->nama_superintendent,
                    'shift_dasar' => 'Malam',
                    'keterangan' => $keteranganMalam,  // Keterangan malam yang sesuai
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];

                // Increment index untuk keterangan malam
                $malamKeteranganIndex++;
            }
        }

        // Proses shift Siang
        if ($item->shift !== 'Malam') {
            foreach ($siang as $waktu) {
                $keteranganSiang = $ket[$siangKeteranganIndex] ?? '';  // Ambil keterangan untuk shift Siang
                $result[] = [
                    'id' => $item->id,
                    'tanggal_pelaporan' => $item->tanggal_pelaporan,
                    'shift' => $item->shift,
                    'area' => $item->area,
                    'lokasi' => $item->lokasi,
                    'jam' => $waktu,
                    'nomor_unit' => $item->nomor_unit,
                    'nik_foreman' => $item->nik_foreman,
                    'nama_foreman' => $item->nama_foreman,
                    'nik_supervisor' => $item->nik_supervisor,
                    'nama_supervisor' => $item->nama_supervisor,
                    'nik_superintendent' => $item->nik_superintendent,
                    'nama_superintendent' => $item->nama_superintendent,
                    'shift_dasar' => 'Siang',
                    'keterangan' => $keteranganSiang,  // Keterangan siang yang sesuai
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];

                // Increment index untuk keterangan siang
                $siangKeteranganIndex++;
            }
        }

        return $result;
    });

        return view('front-loading.index', compact('front'));
    }

    public function excel()
    {
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();
        return Excel::download(new FrontLoading, 'users.xlsx');
        // return view('front-loading.modal.download');
    }

    public function destroy($uuid)
    {
        try {
            FrontLoading::where('uuid', $uuid)->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data', 'error' => $e->getMessage()], 500);
        }
    }
}
