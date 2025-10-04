<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Personal;
use App\Models\PitstopReport;
use App\Models\PitstopReportDesc;
use App\Models\Section;
use App\Models\Shift;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class FormPengawasPitstopController extends Controller
{
    //
    public function insert()
    {
        $unit = Unit::select('VHC_ID', 'VHC_ACTIVE')
            ->where('VHC_ACTIVE', 1)
            ->get();

        // $supervisor = Personal::select('ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY')->where('ROLETYPE', 3)->get();
        $supervisor = User::select(
            'nik as NRP',
            'name as PERSONALNAME',
            'role as JABATAN'
            )->where('role', 'SUPERVISOR')
            ->where('id', '!=', 95)
            ->where('statusenabled', true)->get();
        $shift = Shift::where('statusenabled', true)->get();
        $area = Area::where('statusenabled', true)->get();
        $operator = Personal::select('ID', 'NRP', 'USERNAME', 'PERSONALNAME', 'EPIGONIUSERNAME', 'ROLETYPE', 'SYS_CREATEDBY', 'SYS_UPDATEDBY')->where('ROLETYPE', 0)->get();

        $daily = PitstopReport::where('foreman_id', Auth::user()->id)
            ->where(function ($query) {
                $query->where('is_draft', true)
                    ->orWhere('is_draft', false);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if ($daily == null) {
            $daily = null;
        } else {
            if ($daily['is_draft'] == false ) {
                $daily = null;
            }
        }

        if ($daily) {
            $daily['nik_supervisor'] = $daily['nik_supervisor'] . '|' . $daily['nama_supervisor'];

            if (!empty($daily['date'])) {
                $tanggalDasar = new DateTimeImmutable($daily['date']);
                $daily['date'] = $tanggalDasar ? $tanggalDasar->format('m/d/Y') : $daily['date'];
            }
        }

        $unitPitstops = [];

        if ($daily) {
            $unitPitstops = PitstopReportDesc::where('report_id', $daily->id)
            ->where('is_draft', true)
            ->where('statusenabled', true)
            ->get();

        }

        $data = [
            'shift' => $shift,
            'area' => $area,
            'unit' => $unit,
            'operator' => $operator,
            'supervisor' => $supervisor,
        ];
        // dd($daily);

        return view('form-pengawas-pitstop.insert', compact('data', 'daily', 'unitPitstops'));
    }

    public function saveAsDraft(Request $request)
    {
        try {
            $typeDraft = $request->actionType === 'finish' ? false : true;
            $uuid = $request->uuid ?: Uuid::uuid4()->toString();

            // Ambil data lama kalau ada
            $dailyReport = PitstopReport::firstOrNew(['uuid' => $uuid]);

            // Supervisor
            $nikSupervisor = null;
            $namaSupervisor = null;
            if (!empty($request->nik_supervisor)) {
                [$nikSupervisor, $namaSupervisor] = explode('|', $request->nik_supervisor) + [null, null];
            } else {
                $nikSupervisor = $dailyReport->nik_supervisor ?? null;
                $namaSupervisor = $dailyReport->nama_supervisor ?? null;
            }

            // Data utama
            $data = [
                'uuid' => $uuid,
                'foreman_id' => Auth::id(),
                'statusenabled' => true,
                'date' => $request->filled('date')
                    ? now()->parse($request->date)->format('Y-m-d')
                    : null,
                'shift_id' => $request->shift_id,
                'area_id' => $request->area_id,
                'nik_supervisor' => $nikSupervisor,
                'nama_supervisor' => $namaSupervisor,
                'is_draft' => $typeDraft,
                'catatan_pengawas' => $request->catatan_pengawas,
            ];

            // Role SUPERVISOR
            if (Auth::user()->role === 'SUPERVISOR') {
                $data['nik_supervisor'] = Auth::user()->nik;
                $data['nama_supervisor'] = Auth::user()->name;
                // $data['catatan_verified_supervisor'] = $request->catatan_pitstop;
                $data['verified_supervisor'] = Auth::user()->nik;
            }

            // Role FOREMAN
            if (Auth::user()->role === 'FOREMAN') {
                $data['nik_foreman'] = Auth::user()->nik;
                $data['nama_foreman'] = Auth::user()->name;
                // $data['catatan_verified_foreman'] = $request->catatan_pitstop;
                $data['verified_foreman'] = Auth::user()->nik;
            }

            // Simpan report utama
            $dailyReport->fill($data);
            $dailyReport->save();

            // Simpan detail unit
            if (!empty($request->unit_pitstop)) {
                $unitSupports = json_decode($request->unit_pitstop, true);

                foreach ($unitSupports as $value) {
                    // Ubah string "null" jadi NULL
                    foreach ($value as $k => $v) {
                        if ($v === 'null') {
                            $value[$k] = null;
                        }
                    }

                    $statusUnitBreakdown = !empty($value['status_unit_breakdown'])
                    ? Carbon::parse($value['status_unit_breakdown'])->format('Y-m-d H:i:s')
                    : null;

                $statusUnitReady = !empty($value['status_unit_ready'])
                    ? Carbon::parse($value['status_unit_ready'])->format('Y-m-d H:i:s')
                    : null;

                $statusOprReady = !empty($value['status_opr_ready'])
                    ? Carbon::parse($value['status_opr_ready'])->format('Y-m-d H:i:s')
                    : null;

                    $desc = PitstopReportDesc::firstOrNew([
                        'uuid' => $value['uuid'] ?? Uuid::uuid4()->toString(),
                    ]);

                    $desc->fill([
                        'report_uuid' => $dailyReport->uuid,
                        'report_id' => $dailyReport->id,
                        'statusenabled' => true,
                        'no_unit' => $value['nomor_unit'] ?? null,
                        'opr_settingan' => $value['opr_settingan'] ?? null,
                        'nama_opr_settingan' => $value['nama_opr_settingan'] ?? null,
                        'status_unit_breakdown' => $statusUnitBreakdown,
                        'status_unit_ready' => $statusUnitReady,
                        'status_opr_ready' => $statusOprReady,
                        'opr_ready' => $value['opr_ready'] ?? null,
                        'nama_opr_ready' => $value['nama_opr_ready'] ?? null,
                        'keterangan' => $value['keterangan'] ?? null,
                        'is_draft' => $typeDraft,
                    ]);

                    $desc->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully!',
                'uuid' => $dailyReport->uuid,
                'data' => $dailyReport,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to save draft: ' . $th->getMessage()
            ], 500);
        }
    }
}
