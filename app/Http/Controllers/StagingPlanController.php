<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaStagingPlan;
use App\Models\Shift;
use App\Models\StagingPlan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $pit = AreaStagingPlan::where('statusenabled', true)->orderByDesc('id')->get();

        $stagingQuery = DB::table('STAGING_PLAN as sp')
            ->leftJoin('users as us', 'sp.pic', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'sp.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA_STAGING_PLAN as ar', 'sp.pit_id', '=', 'ar.id')
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
        try {
            $startDate = Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d');
            $endDate   = Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');

            $documentPath = null;

            // =========================
            // UPLOAD PDF KE SFTP (10.10.2.6)
            // =========================
            if ($request->hasFile('document')) {
                $documentPath = Storage::disk('sftp_staging')->putFile(
                    'staging_plan',
                    $request->file('document')
                );

                // UBAH KE URL PENUH
                $documentUrl = 'http://10.10.2.6:93/storage/' . $documentPath;
            }

            DB::table('STAGING_PLAN')->insert([
                'pic'           => Auth::user()->id,
                'uuid'          => (string) Uuid::uuid4(),
                'statusenabled' => true,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                // 'shift_id'      => $request->shift_id,
                'pit_id'        => $request->pit_id,

                // SIMPAN PATH, BUKAN URL
                'document'      => $documentUrl,
            ]);

            return back()->with('success', 'Staging Plan berhasil ditambahkan');

        } catch (\Throwable $th) {
            return back()->with(
                'info',
                'Staging Plan gagal ditambahkan: ' . $th->getMessage()
            );
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
        $data = DB::table('STAGING_PLAN as sp')
            ->leftJoin('users as us', 'sp.pic', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'sp.shift_id', '=', 'sh.id')
            ->leftJoin('REF_AREA_STAGING_PLAN as ar', 'sp.pit_id', '=', 'ar.id')
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
            ->where('sp.uuid', $uuid)
            ->where('sp.statusenabled', true)->first();

        if (!$data) {
            return redirect()->back()->with('info', 'Maaf, staging plan tidak ditemukan');
        }

        $pdfUrl = route('fileStagingPlan.show', $uuid);

        return view('staging-plan.preview', compact('data', 'pdfUrl'));
    }
}
