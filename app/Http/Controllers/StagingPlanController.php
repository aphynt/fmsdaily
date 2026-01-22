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
                'sp.end_date',
                'sp.image'
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
            $imagePath = null;
            $imageUrl = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'staging_plan',
                'public'
            );
            $imageUrl = asset('storage/' . $imagePath);
        }

        DB::table('STAGING_PLAN')->insert([
            'pic' => Auth::user()->id,
            'uuid' => (string) Uuid::uuid4()->toString(),
            'statusenabled' => true,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'shift_id'      => $request->shift_id,
            'pit_id'      => $request->pit_id,
            'image'         => $imageUrl,
        ]);

        return redirect()->back()->with('success', 'Staging Plan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'Staging Plan gagal ditambahkan');
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
}
