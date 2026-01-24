<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Shift;
use App\Models\StagingPlan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class StagingPlanController extends Controller
{
    //
    public function index(Request $request)
    {

        session(['requestTimeLaporanKerjaOBCoal' => $request->all()]);
        $time = new DateTime();
        $filterShift = $request->shift ?? 'Semua';
        $filterPit = $request->pit ?? 5;

        if (empty($request->startStagingPlan) || empty($request->endStagingPlan)) {
            $time = new DateTime();
            $start = new DateTime($time->format('Y-m-d'));
            $end   = new DateTime($time->format('Y-m-d'));
        } else {
            $start = new DateTime($request->startStagingPlan);
            $end   = new DateTime($request->endStagingPlan);
        }

        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted   = $end->format('Y-m-d');

        $shift = Shift::where('statusenabled', true)->get();
        $pit = Area::where('statusenabled', true)->orderByDesc('id')->get();

        $stagingQuery = DB::table('STAGING_PLAN as sp')
            ->leftJoin('users as us', 'sp.pic', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'sp.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA as ar', 'sp.pit_id', '=', 'ar.id')
            ->select(
                'sp.id',
                'sp.uuid',
                'sp.statusenabled',
                'sp.pic',
                'us.name as nama_pic',
                'sh.keterangan as shift',
                'ar.keterangan as pit',
                'sp.start_date',
                'sp.end_date'
            )
            ->where('sp.statusenabled', true)
            ->where(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                $query->whereBetween('sp.start_date', [$startTimeFormatted, $endTimeFormatted])
                    ->orWhereBetween('sp.end_date', [$startTimeFormatted, $endTimeFormatted])
                    ->orWhere(function ($q) use ($startTimeFormatted, $endTimeFormatted) {
                        $q->where('sp.start_date', '<=', $startTimeFormatted)
                            ->where('sp.end_date', '>=', $endTimeFormatted);
                    });
            });

        if ($filterShift != 'Semua') {
            $stagingQuery->where('sp.shift_id', $filterShift);
        }

        if ($filterPit != 5) {
            $stagingQuery->where('sp.pit_id', $filterPit);
        }

        $staging = $stagingQuery->get();

        return view('staging-plan.index', compact('shift', 'staging', 'pit'));
    }

    public function post(Request $request)
    {
        // dd($request->all());

        $startDate = Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d');
        $endDate   = Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');

        // =========================
        // UPLOAD GAMBAR
        // =========================
        try {
            $documentPath = null;
            $documentUrl  = null;

            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store(
                    'staging_plan',
                    'public'
                );

                $documentUrl = asset('storage/' . $documentPath);
            }

        DB::table('STAGING_PLAN')->insert([
            'pic' => Auth::user()->id,
            'uuid' => (string) Uuid::uuid4()->toString(),
            'statusenabled' => true,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'shift_id'      => $request->shift_id,
            'pit_id'      => $request->pit_id,
            'document'      => $documentUrl,
        ]);

        return redirect()->back()->with('success', 'Staging Plan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'Staging Plan gagal ditambahkan..\n'.$th->getMessage());
        }
    }

    public function delete($uuid)
    {
        try {
            StagingPlan::where('uuid', $uuid)->update([
                'statusenabled' => false,
                'deleted_by' => Auth::user()->id
            ]);

            return redirect()->back()->with('success', 'Data berhasil dihapus');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Data gagal dihapus..\n' . $th->getMessage()));
        }
    }

    public function preview($uuid)
    {
        $data = StagingPlan::where('uuid', $uuid)
            ->where('statusenabled', true)
            ->first();

        if (!$data) {
            return redirect()->back()->with('info', 'Maaf, staging plan tidak ditemukan');
        }

        // contoh data DB:
        // http://127.0.0.1:8003/storage/staging_plan/xxxx.pdf
        $fileName = basename($data->document);

        $pdfUrl = route('fileStagingPlan.show', ['path' => $fileName]);

        return view('staging-plan.preview', compact('data', 'fileName', 'pdfUrl'));
    }
}
