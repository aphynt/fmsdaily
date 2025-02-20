<?php

namespace App\Http\Controllers;

use App\Exports\AlatSupportExport;
use App\Models\AlatSupport;
use App\Models\Log;
use App\Models\Personal;
use App\Models\Shift;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AlatSupportController extends Controller
{
    //
    public function index(Request $request)
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

        $support = DB::table('alat_support_t as al')
        ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
        ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
        ->leftJoin('REF_SHIFT as sh2', 'al.shift_operator_id', '=', 'sh2.id')
        ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
        ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
        // ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
        ->select(
            'al.daily_report_id as id',
            'al.uuid',
            'al.jenis_unit',
            'al.alat_unit as nomor_unit',
            'al.nik_operator',
            'al.nama_operator',
            'al.tanggal_operator',
            'sh2.keterangan as shift_operator',
            'al.shift_operator_id',
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
            'al.hm_awal',
            'al.hm_akhir',
            'al.hm_cash',
            'al.keterangan'
        )
        ->where('al.statusenabled', true)
        ->where('dr.statusenabled', true)
        ->whereBetween('tanggal_dasar', [$startTimeFormatted, $endTimeFormatted]);
        if (Auth::user()->role !== 'ADMIN') {
            $support->where('dr.foreman_id', Auth::user()->id);
        }

        $support = $support->get();
        // dd($support);

        $nomor_unit = Unit::select('VHC_ID')
            ->where('VHC_ID', 'NOT LIKE', 'HD%')
            ->get();

        $shift = Shift::where('statusenabled', true)->get();

        $operator = Personal::select
        (
            'ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY'
        )->where('ROLETYPE', 0)->get();

        // dd($support);

        return view('alat-support.index', compact('support', 'nomor_unit', 'shift', 'operator'));
    }

    public function excel(Request $request)
    {

        if (empty(session('requestTimeAlatSupport')['rangeStart']) || empty(session('requestTimeAlatSupport')['rangeEnd'])){
            $time = new DateTime();
            $start = $time;
            $end = $time;

        }else{
            $start = new DateTime(session('requestTimeAlatSupport')['rangeStart']);
            $end = new DateTime(session('requestTimeAlatSupport')['rangeEnd']);
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');

        // dd($bulan);
        return Excel::download(new AlatSupportExport($startTimeFormatted, $endTimeFormatted), 'Alat Support.xlsx');
    }

    public function api(Request $request)
    {

        // return $request->search['value'];
        session(['requestTimeAlatSupport' => $request->all()]);

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

        // Ambil parameter untuk pagination
        $start = $request->input('start', 0);  // Offset
        $length = $request->input('length', 10); // Default 10 item per halaman
        $draw = $request->input('draw');

        $supportQuery = DB::table('alat_support_t as al')
            ->leftJoin('daily_report_t as dr', 'al.daily_report_id', '=', 'dr.id')
            ->leftJoin('REF_SHIFT as sh', 'dr.shift_dasar_id', '=', 'sh.id')
            ->leftJoin('REF_SHIFT as sh2', 'al.shift_operator_id', '=', 'sh2.id')
            ->leftJoin('REF_AREA as ar', 'dr.area_id', '=', 'ar.id')
            ->leftJoin('REF_LOKASI as lok', 'dr.lokasi_id', '=', 'lok.id')
            // ->leftJoin('users as us', 'dr.foreman_id', '=', 'us.id')
            ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'dr.nik_foreman', '=', 'gl.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'dr.nik_supervisor', '=', 'spv.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'dr.nik_superintendent', '=', 'spt.NRP')
            ->select(
                'al.daily_report_id as id',
                'al.uuid',
                'al.jenis_unit',
                'al.alat_unit as nomor_unit',
                'al.nik_operator',
                'al.nama_operator',
                'al.tanggal_operator',
                'sh2.keterangan as shift_operator',
                'al.shift_operator_id',
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
                'al.hm_awal',
                'al.hm_akhir',
                DB::raw('(al.hm_akhir - al.hm_awal) AS total_hm'),
                'al.hm_cash',
                'al.keterangan',
            )
            ->where('al.statusenabled', true)
            ->where('dr.statusenabled', true)
            // ->where('')
            ->whereBetween('dr.tanggal_dasar', [$startTimeFormatted, $endTimeFormatted]);
            if ($request->search['value']) {
                $searchValue = '%' . $request->search['value'] . '%';

                // Daftar kolom yang ingin dicari
                $columnsToSearch = [
                    'al.jenis_unit', 'al.alat_unit', 'al.nik_operator', 'al.nama_operator', 'sh2.keterangan',
                    'dr.nik_foreman', 'gl.PERSONALNAME', 'dr.tanggal_dasar', 'sh.keterangan', 'ar.keterangan', 'lok.keterangan',
                    'dr.nik_supervisor', 'spv.PERSONALNAME', 'dr.nik_superintendent', 'spt.PERSONALNAME', 'al.hm_awal',
                    'al.hm_akhir', 'al.hm_cash', 'al.keterangan'
                ];

                // Looping untuk menambahkan kondisi pencarian untuk setiap kolom
                $supportQuery->where(function($query) use ($columnsToSearch, $searchValue) {
                    foreach ($columnsToSearch as $column) {
                        $query->orWhere($column, 'like', $searchValue);
                    }
                });
            }
            if (Auth::user()->role !== 'ADMIN') {
                $supportQuery->where('dr.foreman_id', Auth::user()->id);
            }
        $filteredRecords = $supportQuery->count();
        $support = $supportQuery->skip($start)->take($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $filteredRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $support
        ]);

    }

    public function destroy($id)
    {
        try {
            AlatSupport::findOrFail($id)->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $uuid)
    {
        $support  = AlatSupport::where('uuid', $uuid)->first();
		$hm_awal = $request->input('hm_awal');
        $hm_akhir = $request->input('hm_akhir');

        $hm_awal = str_replace(',', '.', $hm_awal);
        $hm_akhir = str_replace(',', '.', $hm_akhir);
        try {

            Log::create([
                'tanggal_loging' => now(),
                'jenis_loging' => 'Alat Support',
                'nama_user' => Auth::user()->id,
                'nik' => Auth::user()->nik,
                'keterangan' => 'Mnegubah alat support dengan nama: '. $support->nama_operator . ', NIK: '. $support->nik_operator . ', Alat Unit: '. $support->alat_unit,
            ]);

            $updateData = [

                'tanggal_operator' => $request->tanggal_operator,
                'hm_awal' => $hm_awal,
                'hm_akhir' => $hm_akhir,
                'hm_total' => $hm_akhir - $hm_awal,
                'hm_cash' => $request->hm_cash,
                'keterangan' => $request->keterangan,
            ];

            if ($request->has('alat_unit')) {
                $updateData['alat_unit'] = $request->alat_unit;
            }
            if ($request->has('shift_operator')) {
                $updateData['shift_operator_id'] = $request->shift_operator;
            }
            if ($request->has('nama_operator')) {

                $operator = explode('|',  $request->nama_operator);
                $nikOperator = $operator[0];
                $namaOperator = trim($operator[1]);
                $jenisUnit = substr($request->alat_unit, 0, 2);


                $updateData['jenis_unit'] = $jenisUnit;
                $updateData['nik_operator'] = $nikOperator;
                $updateData['nama_operator'] = $namaOperator;
            }

            // Lakukan update
            AlatSupport::where('uuid', $uuid)->update($updateData);

            return redirect()->back()->with('success', 'Alat Support berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'Alat Support gagal diupdate');
        }
    }

}
