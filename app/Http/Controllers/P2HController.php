<?php

namespace App\Http\Controllers;

use App\Models\ChecklistP2H;
use App\Models\ChecklistP2HDetail;
use App\Models\FLTShift;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class P2HController extends Controller
{
    //
    public function index()
    {
        $shift = FLTShift::all();
        $data = [
            'shift' => $shift
        ];

        return view('safety.p2h.index', compact('data'));
    }

    public function api(Request $request)
    {
        $start = new DateTime($request->tanggalP2H);
        $tanggalP2H = $start->format('Y-m-d');

        $offset = $request->input('start', 0);   // Offset
        $length = $request->input('length', 10); // Default 10 items
        $draw = $request->input('draw');

        $verifikator = DB::table('OPR_CHECKLISTP2H as p2h')
                ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
                ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP')
                ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
                ->select(
                    'p2h.VHC_ID',
                    'p2h.OPR_SHIFTNO',
                    'p2h.OPR_REPORTTIME',
                    'p2h.VERIFIED_MEKANIK',
                    'mec.PERSONALNAME as NAMAMEKANIK',
                    'p2h.VERIFIED_FOREMAN',
                    'gl.PERSONALNAME as NAMAFOREMAN',
                    'p2h.VERIFIED_SUPERVISOR',
                    'spv.PERSONALNAME as NAMASUPERVISOR',
                    'p2h.VERIFIED_SUPERINTENDENT',
                )->get();

        $supportQuery = DB::table('FOCUS.dbo.OPR_OPRCHECKLIST as A')
            ->select(
                'A.ID',
                DB::raw("FORMAT(A.OPR_REPORTTIME, 'yyyy-MM-dd HH:mm:ss') as OPR_REPORTTIME"),
                'A.OPR_SHIFTDATE',
                'A.OPR_SHIFTNO',
                'B.SHIFTDESC as OPR_SHIFTDESC',
                'A.OPR_NRP',
                'D.PERSONALNAME',
                'A.VHC_ID',
                'A.MTR_HOURMETER',
                DB::raw('COALESCE(C.VAL_NOTOK, 0) as VAL_NOTOK'),
                'p2h.VERIFIED_MEKANIK',
                'mec.PERSONALNAME as NAMAMEKANIK',
                'p2h.VERIFIED_FOREMAN',
                'gl.PERSONALNAME as NAMAFOREMAN',
                'p2h.VERIFIED_SUPERVISOR',
                'spv.PERSONALNAME as NAMASUPERVISOR'
            )
            ->leftJoin('FOCUS.dbo.FLT_SHIFT as B', 'A.OPR_SHIFTNO', '=', 'B.SHIFTNO')
            ->leftJoin('FOCUS.dbo.PRS_PERSONAL as D', 'A.OPR_NRP', '=', 'D.NRP')
            ->leftJoin(DB::raw('(
                SELECT VHC_ID, OPR_REPORTTIME, COUNT(*) AS VAL_NOTOK
                FROM FOCUS.dbo.OPR_OPRCHECKLISTITEM
                WHERE CHECKLISTVAL = 0
                GROUP BY VHC_ID, OPR_REPORTTIME
            ) as C'), function($join) {
                $join->on('C.VHC_ID', '=', 'A.VHC_ID')
                    ->on('C.OPR_REPORTTIME', '=', 'A.OPR_REPORTTIME');
            })
            ->leftJoin('OPR_CHECKLISTP2H as p2h', function($join) {
                $join->on('A.VHC_ID', '=', 'p2h.VHC_ID')
                    ->on('A.OPR_REPORTTIME', '=', 'p2h.OPR_REPORTTIME');
            })
            ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
            ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP');
            if (in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK'])) {
            $supportQuery->where('VAL_NOTOK', '>=', '1');
        }

        // Optional: filter berdasarkan kata kunci pencarian
        if ($request->search['value']) {
            $searchValue = '%' . $request->search['value'] . '%';
            $columnsToSearch = ['A.OPR_NRP', 'D.PERSONALNAME', 'A.VHC_ID'];

            $supportQuery->where(function($query) use ($columnsToSearch, $searchValue) {
                foreach ($columnsToSearch as $column) {
                    $query->orWhere($column, 'like', $searchValue);
                }
            });
        }

        // Filter shift jika ada
        if (!empty($request->shiftP2H)) {
            $supportQuery->where('A.OPR_SHIFTNO', $request->shiftP2H);
        }

        // Filter tanggal jika ada
        if (!empty($request->tanggalP2H)) {
            $supportQuery->where('A.OPR_SHIFTDATE', $tanggalP2H);
        }

        if(in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']) and Auth::user()->section == 'WHEEL') {
            $supportQuery->where(function($query) {
                $query->where('A.VHC_ID', 'like', 'MG%')
                    ->orWhere('A.VHC_ID', 'like', 'HD%');
            });
        }elseif(in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']) and in_array(Auth::user()->section, ['TRACK EXCA'])) {
            $supportQuery->where(function($query) {
                $query->where('A.VHC_ID', 'like', 'EX%');
            });
        }elseif(in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']) and in_array(Auth::user()->section, ['TRACK DOZER'])) {
            $supportQuery->where(function($query) {
                $query->where('A.VHC_ID', 'like', 'BD%');
            });
        }

        //menampilkan hanya P2H yang diatas 0, dengan role sbb



        // Hanya ambil data yang sudah diverifikasi oleh foreman atau supervisor
        // $supportQuery->where(function($query) {
        //     $query->whereNotNull('p2h.VERIFIED_FOREMAN')
        //           ->orWhereNotNull('p2h.VERIFIED_SUPERVISOR');
        // });

        // Hitung total hasil filter
        $filteredRecords = $supportQuery->count();

        // Ambil data dengan urutan dan paginasi
        $supportQuery = $supportQuery
            ->orderByDesc('VAL_NOTOK')

            ->orderBy('A.VHC_ID')
            ->orderBy('A.OPR_REPORTTIME')
            ->offset($offset)
            ->limit($length)
            ->get();

        // return $supportQuery;




        // $supportQuery->whereNotExists(function ($query) use ($verifikator) {
        //     $query->select(DB::raw(1))
        //         ->from('OPR_CHECKLISTP2H as p2h')
        //         ->whereColumn('p2h.VHC_ID', 'A.VHC_ID')
        //         ->whereColumn('p2h.OPR_SHIFTNO', 'A.OPR_SHIFTNO')
        //         ->whereDate('p2h.OPR_REPORTTIME', DB::raw("CAST(A.OPR_SHIFTDATE AS DATE)"));
        // });


        // Ambil data untuk halaman saat ini

        // Return JSON response
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $filteredRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $supportQuery,
        ]);
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

        $data = DB::table('OPR_CHECKLISTP2H as p2h')
        ->leftJoin('focus.dbo.FLT_SHIFT as sh', 'p2h.OPR_SHIFTNO', '=', 'sh.SHIFTNO')
        ->leftJoin('focus.dbo.PRS_PERSONAL as opr', 'p2h.VERIFIED_OPERATOR', '=', 'opr.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'p2h.VERIFIED_SUPERINTENDENT', '=', 'spt.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
        ->select(
            'p2h.UUID',
            'p2h.STATUSENABLED',
            'p2h.VHC_ID',
            'p2h.MTR_HOURMETER',
            'sh.SHIFTDESC',
            'p2h.OPR_REPORTTIME',
            'p2h.CREATED_AT',
            'p2h.VERIFIED_OPERATOR',
            'p2h.DATEVERIFIED_OPERATOR',
            'opr.PERSONALNAME as NAMAOPERATOR',
            'p2h.VERIFIED_MEKANIK',
            'p2h.DATEVERIFIED_MEKANIK',
            'mec.PERSONALNAME as NAMAMEKANIK',
            'p2h.VERIFIED_FOREMAN',
            'gl.PERSONALNAME as NAMAFOREMAN',
            'p2h.DATEVERIFIED_FOREMAN',
            'p2h.VERIFIED_SUPERVISOR',
            'spv.PERSONALNAME as NAMASUPERVISOR',
            'p2h.DATEVERIFIED_SUPERVISOR',
            'p2h.VERIFIED_SUPERINTENDENT',
            'spt.PERSONALNAME as NAMASUPERINTENDENT',
            'p2h.DATEVERIFIED_SUPERINTENDENT',
        )
        ->whereBetween(DB::raw('CAST(p2h.OPR_REPORTTIME AS DATE)'), [$startTimeFormatted, $endTimeFormatted])

        ->where('p2h.STATUSENABLED', true);
        $data = $data->where(function($query) {
            if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
                $query->where('p2h.VERIFIED_FOREMAN', Auth::user()->nik)
                  ->orWhere('p2h.VERIFIED_SUPERVISOR', Auth::user()->nik)
                  ->orWhere('p2h.VERIFIED_SUPERINTENDENT', Auth::user()->nik)
                  ->orWhere('p2h.VERIFIED_MEKANIK', Auth::user()->nik);
            }
        });
        $data = $data->get();

        return view('safety.p2h.show', compact('data'));
    }

    public function detail(Request $request)
    {
       $detail = DB::table('FOCUS.dbo.OPR_OPRCHECKLISTITEM as A')
        ->select(
            'A.ID',
            'A.CHECKLISTGROUPID',
            'A.CHECKLISTITEMID',
            'B.CHECKLISTITEMDESCRIPTION',
            'A.CHECKLISTNOTES',
            'A.CHECKLISTVAL',
            'A.CHECKLISTVAL as VAL',
            'A.OPR_REPORTTIME',
            'A.OPR_SHIFTDATE',
            'A.OPR_SHIFTNO',
            'A.VHC_ID',
        )
        ->leftJoin('FOCUS.dbo.FLT_EQUCHECKLISTITEM as B', function($join) {
            $join->on('A.EQU_TYPEID', '=', 'B.EQU_TYPEID')
                ->on('A.CHECKLISTGROUPID', '=', 'B.CHECKLISTGROUPID')
                ->on('A.CHECKLISTITEMID', '=', 'B.CHECKLISTITEMID');
        })
        ->leftJoin('FOCUS.dbo.FLT_EQUCHECKLISTGROUP as C', function($join) {
            $join->on('A.EQU_TYPEID', '=', 'C.EQU_TYPEID')
                ->on('A.CHECKLISTGROUPID', '=', 'C.CHECKLISTGROUPID');
        })
        ->where('A.VHC_ID', $request['VHC_ID'])
        ->whereRaw("DATEADD(ms, -DATEPART(ms, A.OPR_REPORTTIME), A.OPR_REPORTTIME) = ?", [$request['OPR_REPORTTIME']])
        ->orderBy('A.CHECKLISTGROUPID')
        ->orderBy('B.CHECKLISTITEMDESCRIPTION')
        ->get();

        // dd($detail);



        $jumlahAATerisi = $detail->filter(function ($item) {
            return $item->CHECKLISTVAL == 0 && $item->CHECKLISTGROUPID == 'AA';
        })->count();

        // dd($jumlahAATerisi);

        $checkdataP2H = ChecklistP2H::where('VHC_ID', $detail->first()->VHC_ID)
        ->where('OPR_SHIFTNO', $detail->first()->OPR_SHIFTNO)
        ->where('OPR_REPORTTIME', $detail->first()->OPR_REPORTTIME)->first();

        $verifikasiMekanik = false;
        $detailP2H = "";

        if($checkdataP2H != null){
            if($checkdataP2H->VERIFIED_MEKANIK != null){
                $verifikasiMekanik = true;

                $detailP2H = DB::table('OPR_CHECKLISTP2H as p2h')
                ->leftJoin('OPR_CHECKLISTP2H_DETAIL as ph', 'p2h.UUID', '=', 'ph.UUID_OPR_CHECKLISTP2H')
                ->select(
                    'ph.GROUPID as CHECKLISTGROUPID',
                    'ph.GROUPID as CHECKLISTGROUPDESCRIPTION',
                    'ph.ITEMDESCRIPTION as CHECKLISTITEMDESCRIPTION',
                    'ph.VALUE as CHECKLISTVAL',
                    'ph.NOTES as CHECKLISTNOTES',
                    'ph.CATATAN_MEKANIK',
                    'ph.KBJ',
                    'ph.JAWABAN',
                    'p2h.OPR_REPORTTIME',
                    'p2h.OPR_SHIFTDATE',
                    'p2h.OPR_SHIFTNO',
                    'p2h.VHC_ID',
                    'p2h.MTR_HOURMETER',
                    )
                ->where('UUID_OPR_CHECKLISTP2H', $checkdataP2H->UUID)->where('p2h.STATUSENABLED', true)->get();

                                // Langkah 1: Normalisasi struktur $detailP2H agar fieldnya sepadan dengan $detail
                $normalizedDetailP2H = $detailP2H->map(function ($item) {
                    return (object)[
                        'ID' => null,
                        'CHECKLISTGROUPID' => $item->CHECKLISTGROUPID,
                        'CHECKLISTITEMID' => null,
                        'CHECKLISTITEMDESCRIPTION' => $item->CHECKLISTITEMDESCRIPTION,
                        'CHECKLISTNOTES' => $item->CHECKLISTNOTES,
                        'CHECKLISTVAL' => $item->CHECKLISTVAL,
                        'VAL' => $item->CHECKLISTVAL,
                        'CATATAN_MEKANIK' => $item->CATATAN_MEKANIK ?? null,
                        'KBJ' => $item->KBJ ?? null,
                        'JAWABAN' => $item->JAWABAN ?? null,
                        'OPR_REPORTTIME' => $item->OPR_REPORTTIME,
                        'OPR_SHIFTDATE' => $item->OPR_SHIFTDATE,
                        'OPR_SHIFTNO' => $item->OPR_SHIFTNO,
                        'VHC_ID' => $item->VHC_ID,
                        'SOURCE' => 'P2H',
                    ];
                });

                // Langkah 2: Tambahkan flag 'SOURCE' ke $detail untuk penanda asal data
                $normalizedDetail = $detail->map(function ($item) {
                    return (object)[
                    'ID' => $item->ID,
                    'CHECKLISTGROUPID' => $item->CHECKLISTGROUPID,
                    'CHECKLISTITEMID' => $item->CHECKLISTITEMID,
                    'CHECKLISTITEMDESCRIPTION' => $item->CHECKLISTITEMDESCRIPTION,
                    'CHECKLISTNOTES' => $item->CHECKLISTNOTES,
                    'CHECKLISTVAL' => $item->CHECKLISTVAL,
                    'VAL' => $item->VAL,
                    'CATATAN_MEKANIK' => null,  // Ditambahkan manual karena tidak ada di query
                    'KBJ' => null,
                    'JAWABAN' => null,
                    'OPR_REPORTTIME' => $item->OPR_REPORTTIME,
                    'OPR_SHIFTDATE' => $item->OPR_SHIFTDATE,
                    'OPR_SHIFTNO' => $item->OPR_SHIFTNO,
                    'VHC_ID' => $item->VHC_ID,
                    'SOURCE' => 'DETAIL',
                ];
                });

                // Langkah 3: Gabungkan dengan key unik berdasarkan field penting
                $keyFn = fn($item) => implode('|', [
                    $item->CHECKLISTGROUPID,
                    $item->CHECKLISTITEMDESCRIPTION,
                    $item->VHC_ID,
                    $item->OPR_REPORTTIME
                ]);

                // Step 4: Buat collection awal dari $detailP2H
                $combined = $normalizedDetailP2H->keyBy($keyFn);

                // Step 5: Tambahkan dari $detail jika belum ada
                $normalizedDetail->each(function ($item) use (&$combined, $keyFn) {
                    $key = $keyFn($item);
                    if (! $combined->has($key)) {
                        $combined->put($key, $item);
                    }
                });

                // Final output
                $detailP2H = $combined->values();


            }else{
                $verifikasiMekanik = false;
            }
        }

        // dd($detailP2H);

        if($checkdataP2H == null){
            ChecklistP2H::create([
                'UUID' => (string) Uuid::uuid4()->toString(),
                'STATUSENABLED' => true,
                'CREATED_BY' => Auth::user()->id,
                'VHC_ID' => $detail->first()->VHC_ID,
                'MTR_HOURMETER' => $request['MTR_HOURMETER'],
                'OPR_SHIFTNO' => $detail->first()->OPR_SHIFTNO,
                'OPR_REPORTTIME' => $detail->first()->OPR_REPORTTIME,
                'OPR_SHIFTDATE' => $detail->first()->OPR_SHIFTDATE,
                'VERIFIED_OPERATOR' => $request['OPR_NRP'],
                'DATEVERIFIED_OPERATOR' => $detail->first()->OPR_REPORTTIME,
            ]);
        }
        // dd($detail);

        // dd($verifikasiMekanik);

        if(substr($detail->first()->VHC_ID, 0, 2) == 'EX'){
            return view('safety.p2h.detail.ex', compact('detail', 'jumlahAATerisi', 'verifikasiMekanik', 'detailP2H', 'checkdataP2H'));

        }elseif(substr($detail->first()->VHC_ID, 0, 2) == 'HD'){
            return view('safety.p2h.detail.hd', compact('detail', 'jumlahAATerisi', 'verifikasiMekanik', 'detailP2H', 'checkdataP2H'));

        }elseif(substr($detail->first()->VHC_ID, 0, 2) == 'BD'){
            return view('safety.p2h.detail.bd', compact('detail', 'jumlahAATerisi', 'verifikasiMekanik', 'detailP2H', 'checkdataP2H'));

        }elseif(substr($detail->first()->VHC_ID, 0, 2) == 'MG'){
            return view('safety.p2h.detail.mg', compact('detail', 'jumlahAATerisi', 'verifikasiMekanik', 'detailP2H', 'checkdataP2H'));

        }else{
            return view('safety.p2h.detail.hd', compact('detail', 'jumlahAATerisi', 'verifikasiMekanik', 'detailP2H', 'checkdataP2H'));

        }
    }

    public function preview($uuid)
    {
        $data = DB::table('OPR_CHECKLISTP2H as p2h')
        ->leftJoin('OPR_CHECKLISTP2H_DETAIL as ph', 'p2h.UUID', '=', 'ph.UUID_OPR_CHECKLISTP2H')
        ->leftJoin('focus.dbo.FLT_SHIFT as sh', 'p2h.OPR_SHIFTNO', '=', 'sh.SHIFTNO')
        ->leftJoin('focus.dbo.PRS_PERSONAL as opr', 'p2h.VERIFIED_OPERATOR', '=', 'opr.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'p2h.VERIFIED_SUPERINTENDENT', '=', 'spt.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
        ->select(
            'p2h.UUID',
            'p2h.STATUSENABLED',
            'p2h.VHC_ID',
            'p2h.MTR_HOURMETER',
            'sh.SHIFTDESC',
            'p2h.OPR_REPORTTIME',
            'p2h.VERIFIED_OPERATOR',
            'p2h.DATEVERIFIED_OPERATOR',
            'p2h.VERIFIED_MEKANIK',
            'opr.PERSONALNAME as NAMAOPERATOR',
            'p2h.DATEVERIFIED_MEKANIK',
            'mec.PERSONALNAME as NAMAMEKANIK',
            'p2h.VERIFIED_FOREMAN',
            'gl.PERSONALNAME as NAMAFOREMAN',
            'p2h.DATEVERIFIED_FOREMAN',
            'p2h.VERIFIED_SUPERVISOR',
            'spv.PERSONALNAME as NAMASUPERVISOR',
            'p2h.DATEVERIFIED_SUPERVISOR',
            'p2h.VERIFIED_SUPERINTENDENT',
            'spt.PERSONALNAME as NAMASUPERINTENDENT',
            'p2h.DATEVERIFIED_SUPERINTENDENT',
            'ph.GROUPID',
            'ph.ITEMDESCRIPTION',
            'ph.VALUE',
            'ph.CATATAN_MEKANIK',
            'ph.NOTES',
            'ph.KBJ',
            'ph.JAWABAN',
        )
        ->where('ph.UUID_OPR_CHECKLISTP2H', $uuid)
        ->where('p2h.STATUSENABLED', true)
        ->orderBy('ph.GROUPID')
        ->orderBy('ph.ITEMDESCRIPTION')
        // ->where(function ($query) {
        //     $query->whereNotNull('p2h.DATEVERIFIED_FOREMAN')
        //         ->orWhereNotNull('p2h.DATEVERIFIED_SUPERVISOR');
        // })
        ->get();

        if($data->isEmpty()){
            return redirect()->back()->with('info', 'Maaf, data P2H belum diverifikasi');
        }

        if(substr($data->first()->VHC_ID, 0, 2) == 'EX'){
            return view('safety.p2h.preview.ex', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'HD'){
            return view('safety.p2h.preview.hd', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'BD'){
            return view('safety.p2h.preview.bd', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'MG'){
            return view('safety.p2h.preview.mg', compact('data'));

        }else{
            return view('safety.p2h.preview.hd', compact('data'));

        }
    }

    public function verifiedSuperintendent($uuid)
    {
        try {
            $updateData = [
                'VERIFIED_SUPERINTENDENT' => Auth::user()->nik,
                'DATEVERIFIED_SUPERINTENDENT' => Carbon::now(),
            ];

        ChecklistP2H::where('UUID', $uuid)->update($updateData);

        return redirect()->back()->with('success', 'P2H berhasil diverifikasi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('info', 'P2H gagal diverifikasi');
        }
    }

    public function detail_post(Request $request)
    {
        // dd($request->all());
         if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER'])) {
            return redirect()->back()->with('info', 'Maaf, verifikasi hanya dapat dilakukan oleh pengawas!');
        }

        $vhcId = $request->VHC_ID;
        $shift = $request->OPR_SHIFTNO;
        $reportTime = Carbon::parse($request->OPR_REPORTTIME);

        $checkdataP2H = ChecklistP2H::where('VHC_ID', $vhcId)
        ->where('OPR_SHIFTNO', $shift)
        ->where('OPR_REPORTTIME', $reportTime)->first();

        $groupIds = $request->CHECKLISTGROUPID;
        $descriptions = $request->CHECKLISTITEMDESCRIPTION;
        $values = $request->CHECKLISTVAL;
        $notes = $request->CHECKLISTNOTES;
        $kbjs = $request->KBJ ?? [];
        $mekanik = $request->CATATAN_MEKANIK ?? [];
        $jawabans = $request->JAWABAN ?? [];

        $count = count($groupIds);

        try {
            for ($i = 0; $i < $count; $i++) {
                ChecklistP2HDetail::updateOrInsert(
                    [
                        'UUID_OPR_CHECKLISTP2H' => $checkdataP2H->UUID,
                        'GROUPID' => $groupIds[$i],
                        'ITEMDESCRIPTION' => $descriptions[$i],
                    ],
                    [
                        'UUID' => (string) Uuid::uuid4()->toString(), // <-- Tambahkan UUID
                        'VALUE' => $values[$i],
                        'NOTES' => $notes[$i],
                        'KBJ' => $kbjs[$i] ?? null,
                        'CATATAN_MEKANIK' => $mekanik[$i] ?? null,
                        'JAWABAN' => $jawabans[$i] ?? null,
                        'CREATED_BY' => Auth::user()->id,
                        'STATUSENABLED' => true,
                        'UPDATED_AT' => now(), // kalau pakai timestamps
                    ]
                );
            }
        $updateData = [];
        if (Auth::user()->role == 'FOREMAN') {
            $updateData = [
                'VERIFIED_FOREMAN' => Auth::user()->nik,
                'DATEVERIFIED_FOREMAN' => Carbon::now(),
            ];
        } elseif (Auth::user()->role == 'SUPERVISOR') {
            $updateData = [
                'VERIFIED_SUPERVISOR' => Auth::user()->nik,
                'DATEVERIFIED_SUPERVISOR' => Carbon::now(),
            ];
        } elseif (Auth::user()->role == 'SUPERINTENDENT') {
            $updateData = [
                'VERIFIED_SUPERINTENDENT' => Auth::user()->nik,
                'DATEVERIFIED_SUPERINTENDENT' => Carbon::now(),
            ];
        } else {
            $updateData = [
                'VERIFIED_MEKANIK' => Auth::user()->nik,
                'DATEVERIFIED_MEKANIK' => Carbon::now(),
            ];
        }

        ChecklistP2H::where('UUID', $checkdataP2H->UUID)->update($updateData);

        return redirect()->route('p2h.show')->with('success', 'P2H berhasil diverifikasi');

        } catch (\Throwable $th) {
           return redirect()->route('p2h.show')->with('info', $th->getMessage());
        }

    }

    public function cetak($uuid)
    {
        $data = DB::table('OPR_CHECKLISTP2H as p2h')
        ->leftJoin('OPR_CHECKLISTP2H_DETAIL as ph', 'p2h.UUID', '=', 'ph.UUID_OPR_CHECKLISTP2H')
        ->leftJoin('focus.dbo.FLT_SHIFT as sh', 'p2h.OPR_SHIFTNO', '=', 'sh.SHIFTNO')
        ->leftJoin('focus.dbo.PRS_PERSONAL as opr', 'p2h.VERIFIED_OPERATOR', '=', 'opr.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'p2h.VERIFIED_SUPERINTENDENT', '=', 'spt.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
        ->select(
            'p2h.UUID',
            'p2h.STATUSENABLED',
            'p2h.VHC_ID',
            'p2h.MTR_HOURMETER',
            'sh.SHIFTDESC',
            'p2h.OPR_REPORTTIME',
            'p2h.VERIFIED_OPERATOR',
            'p2h.DATEVERIFIED_OPERATOR',
            'opr.NRP as NRPOPERATOR',
            'opr.PERSONALNAME as NAMAOPERATOR',
            'p2h.VERIFIED_MEKANIK',
            'p2h.DATEVERIFIED_MEKANIK',
            'mec.NRP as NRPMEKANIK',
            'mec.PERSONALNAME as NAMAMEKANIK',
            'p2h.VERIFIED_FOREMAN',
            'gl.NRP as NRPFOREMAN',
            'gl.PERSONALNAME as NAMAFOREMAN',
            'p2h.DATEVERIFIED_FOREMAN',
            'p2h.VERIFIED_SUPERVISOR',
            'spv.NRP as NRPSUPERVISOR',
            'spv.PERSONALNAME as NAMASUPERVISOR',
            'p2h.DATEVERIFIED_SUPERVISOR',
            'p2h.VERIFIED_SUPERINTENDENT',
            'spt.NRP as NRPSUPERINTENDENT',
            'spt.PERSONALNAME as NAMASUPERINTENDENT',
            'p2h.DATEVERIFIED_SUPERINTENDENT',
            'ph.GROUPID',
            'ph.ITEMDESCRIPTION',
            'ph.VALUE',
            'ph.NOTES',
            'ph.KBJ',
            'ph.JAWABAN',
            'ph.CATATAN_MEKANIK',
        )
        ->where('ph.UUID_OPR_CHECKLISTP2H', $uuid)
        ->where('p2h.STATUSENABLED', true)
        ->orderBy('ph.GROUPID')
        ->orderBy('ph.ITEMDESCRIPTION')
        // ->where(function ($query) {
        //     $query->whereNotNull('p2h.DATEVERIFIED_FOREMAN')
        //         ->orWhereNotNull('p2h.DATEVERIFIED_SUPERVISOR');
        // })
        ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            foreach ($data as $item) {
                $item->VERIFIED_OPERATOR = $item->VERIFIED_OPERATOR != null
                    ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->NAMAOPERATOR)
                    : null;

                $item->VERIFIED_MEKANIK = $item->VERIFIED_MEKANIK != null
                    ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->NAMAMEKANIK)
                    : null;

                $item->VERIFIED_FOREMAN = $item->VERIFIED_FOREMAN != null
                    ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->NAMAFOREMAN)
                    : null;

                $item->VERIFIED_SUPERVISOR = $item->VERIFIED_SUPERVISOR != null
                    ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->NAMASUPERVISOR)
                    : null;

                $item->VERIFIED_SUPERINTENDENT = $item->VERIFIED_SUPERINTENDENT != null
                    ? QrCode::size(70)->generate('Telah diverifikasi oleh: ' . $item->NAMASUPERINTENDENT)
                    : null;
            }
        }


        if(substr($data->first()->VHC_ID, 0, 2) == 'EX'){
            return view('safety.p2h.cetak.ex', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'HD'){
            return view('safety.p2h.cetak.hd', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'BD'){
            return view('safety.p2h.cetak.bd', compact('data'));

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'MG'){
            return view('safety.p2h.cetak.mg', compact('data'));

        }else{
            return view('safety.p2h.cetak.hd', compact('data'));

        }
    }

    public function download($uuid)
    {
        $data = DB::table('OPR_CHECKLISTP2H as p2h')
        ->leftJoin('OPR_CHECKLISTP2H_DETAIL as ph', 'p2h.UUID', '=', 'ph.UUID_OPR_CHECKLISTP2H')
        ->leftJoin('focus.dbo.FLT_SHIFT as sh', 'p2h.OPR_SHIFTNO', '=', 'sh.SHIFTNO')
        ->leftJoin('focus.dbo.PRS_PERSONAL as opr', 'p2h.VERIFIED_OPERATOR', '=', 'opr.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as gl', 'p2h.VERIFIED_FOREMAN', '=', 'gl.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spv', 'p2h.VERIFIED_SUPERVISOR', '=', 'spv.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as spt', 'p2h.VERIFIED_SUPERINTENDENT', '=', 'spt.NRP')
        ->leftJoin('focus.dbo.PRS_PERSONAL as mec', 'p2h.VERIFIED_MEKANIK', '=', 'mec.NRP')
        ->select(
            'p2h.UUID',
            'p2h.STATUSENABLED',
            'p2h.VHC_ID',
            'p2h.MTR_HOURMETER',
            'sh.SHIFTDESC',
            'p2h.OPR_REPORTTIME',
            'p2h.VERIFIED_OPERATOR',
            'p2h.DATEVERIFIED_OPERATOR',
            'opr.NRP as NRPOPERATOR',
            'opr.PERSONALNAME as NAMAOPERATOR',
            'p2h.VERIFIED_MEKANIK',
            'p2h.DATEVERIFIED_MEKANIK',
            'mec.NRP as NRPMEKANIK',
            'mec.PERSONALNAME as NAMAMEKANIK',
            'p2h.VERIFIED_FOREMAN',
            'gl.NRP as NRPFOREMAN',
            'gl.PERSONALNAME as NAMAFOREMAN',
            'p2h.DATEVERIFIED_FOREMAN',
            'p2h.VERIFIED_SUPERVISOR',
            'spv.NRP as NRPSUPERVISOR',
            'spv.PERSONALNAME as NAMASUPERVISOR',
            'p2h.DATEVERIFIED_SUPERVISOR',
            'p2h.VERIFIED_SUPERINTENDENT',
            'spt.NRP as NRPSUPERINTENDENT',
            'spt.PERSONALNAME as NAMASUPERINTENDENT',
            'p2h.DATEVERIFIED_SUPERINTENDENT',
            'ph.GROUPID',
            'ph.ITEMDESCRIPTION',
            'ph.VALUE',
            'ph.NOTES',
            'ph.KBJ',
            'ph.JAWABAN',
            'ph.CATATAN_MEKANIK',
        )
        ->where('ph.UUID_OPR_CHECKLISTP2H', $uuid)
        ->where('p2h.STATUSENABLED', true)
        ->orderBy('ph.GROUPID')
        ->orderBy('ph.ITEMDESCRIPTION')
        // ->where(function ($query) {
        //     $query->whereNotNull('p2h.DATEVERIFIED_FOREMAN')
        //         ->orWhereNotNull('p2h.DATEVERIFIED_SUPERVISOR');
        // })
        ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        } else {
            $item = $data->first(); // ambil data pertama

            $qrTempFolder = storage_path('app/qr-temp');
                if (!File::exists($qrTempFolder)) {
                    File::makeDirectory($qrTempFolder, 0755, true);
                }

                if($item->VERIFIED_OPERATOR != null){
                    $fileName = 'VERIFIED_OPERATOR' . $item->UUID . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    QrCode::size(150)->format('png')->generate('Telah dibuat oleh: ' . $item->NAMAOPERATOR, $filePath);
                    $item->VERIFIED_OPERATOR = $filePath;
                }else{
                    $item->VERIFIED_OPERATOR == null;
                }

                if($item->VERIFIED_MEKANIK != null){
                    $fileName = 'VERIFIED_MEKANIK' . $item->UUID . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->VERIFIED_MEKANIK)]), $filePath);
                    $item->VERIFIED_MEKANIK = $filePath;
                }else{
                    $item->VERIFIED_MEKANIK == null;
                }

                if($item->VERIFIED_FOREMAN != null){
                    $fileName = 'VERIFIED_FOREMAN' . $item->UUID . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->VERIFIED_FOREMAN)]), $filePath);
                    $item->VERIFIED_FOREMAN = $filePath;
                }else{
                    $item->VERIFIED_FOREMAN == null;
                }

                if($item->VERIFIED_SUPERVISOR != null){
                    $fileName = 'VERIFIED_SUPERVISOR' . $item->UUID . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->VERIFIED_SUPERVISOR)]), $filePath);
                    $item->VERIFIED_SUPERVISOR = $filePath;
                }else{
                    $item->VERIFIED_SUPERVISOR == null;
                }

                if($item->VERIFIED_SUPERINTENDENT != null){
                    $fileName = 'VERIFIED_SUPERINTENDENT' . $item->UUID . '.png';
                    $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                    QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->VERIFIED_SUPERINTENDENT)]), $filePath);
                    $item->VERIFIED_SUPERINTENDENT = $filePath;
                }else{
                    $item->VERIFIED_SUPERINTENDENT == null;
                }

        }



        if(substr($data->first()->VHC_ID, 0, 2) == 'EX'){
            $pdf = PDF::loadView('safety.p2h.download.ex', compact('data'));
            return $pdf->download('P2H Excavator-'. $data->first()->OPR_REPORTTIME .'-'. $data->first()->SHIFTDESC .'-'. $data->first()->VHC_ID .'.pdf');

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'HD'){
            $pdf = PDF::loadView('safety.p2h.download.hd', compact('data'));
            return $pdf->download('P2H Heavy Dump Truck-'. $data->first()->OPR_REPORTTIME .'-'. $data->first()->SHIFTDESC .'-'. $data->first()->VHC_ID .'.pdf');

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'BD'){
            $pdf = PDF::loadView('safety.p2h.download.bd', compact('data'));
            return $pdf->download('P2H Bull Dozer-'. $data->first()->OPR_REPORTTIME .'-'. $data->first()->SHIFTDESC .'-'. $data->first()->VHC_ID .'.pdf');

        }elseif(substr($data->first()->VHC_ID, 0, 2) == 'MG'){
            $pdf = PDF::loadView('safety.p2h.download.mg', compact('data'));
            return $pdf->download('P2H Motor Grader-'. $data->first()->OPR_REPORTTIME .'-'. $data->first()->SHIFTDESC .'-'. $data->first()->VHC_ID .'.pdf');

        }else{
            return redirect()->back()->with('info', 'Data tidak ditemukan');

        }
    }
}
