<?php

namespace App\Http\Controllers;

use App\Exports\JobPendingExport;
use App\Models\JobPending;
use App\Models\JobPendingDesc;
use App\Models\JobPendingNote;
use App\Models\Personal;
use App\Models\Section;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class JobPendingController extends Controller
{
    //
    public function index(Request $request)
    {
        $shift = Shift::where('statusenabled', true)->get();
        $section = Section::where('statusenabled', true)->get();

        $filterShift = $request->shift ?? 'Semua';
        $filterSection = $request->section ?? 'Semua';
        $filterDate = $request->tanggalJobPending ?? Carbon::today()->format('Y-m-d');

        $dataQuery = DB::table('JOB_PENDING as jp')
            ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
            ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
            ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
            ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
            ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
            ->select(
                'jp.id',
                'jp.uuid',
                'us.name as pic',
                'us.nik as nik_pic',
                'jp.statusenabled',
                'sh.keterangan as shift',
                'sec.keterangan as section',
                'jp.date',
                'jp.tanggal_pending',
                'jp.lokasi',
                'jp.dibuat as nik_dibuat',
                'db.name as nama_dibuat',
                'jp.diterima as nik_diterima',
                'dt.name as nama_diterima',
                'jp.verified_dibuat',
                'jp.verified_diterima',
            )
            ->where('jp.statusenabled', true);

        // Filter shift
        if ($filterShift != 'Semua') {
            $dataQuery->where('sh.id', $filterShift);
        }

        if ($filterSection != 'Semua') {
            $dataQuery->where('sec.id', $filterSection);
        }

        // Filter tanggal
        if ($filterDate) {
            $dataQuery->whereDate('jp.date', Carbon::parse($filterDate)->format('Y-m-d'));
        }

        $data = $dataQuery->get();

        // dd($data);

        return view('job-pending.index', compact('data', 'shift', 'section', 'filterShift', 'filterSection', 'filterDate'));
    }


    public function insert()
    {
        $rekan = Personal::whereIn('ROLETYPE', [2, 3, 4])
        ->select('*', DB::raw("CASE WHEN ROLETYPE = 2 THEN 'FOREMAN' WHEN ROLETYPE = 3 THEN 'SUPERVISOR' WHEN ROLETYPE = 4 THEN 'SUPERINTENDENT' ELSE 'UNKNOWN' END as JABATAN "))
        ->where('NRP', '!=', Auth::user()->nik)
        ->orderBy('ROLETYPE')->get();

        $shift = Shift::where('statusenabled', true)->get();
        $section = Section::where('statusenabled', true)->get();
        $data = [
            'rekan' => $rekan,
            'shift' => $shift,
            'section' => $section,
        ];

        return view('job-pending.insert', compact('data'));
    }

    public function post(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $baseDate = Carbon::parse($request->date)->format('Y-m-d');

        // cek shift, misal shift_id = 1 untuk Siang, 2 untuk Malam
        if ($request->shift == '2') {
            $finalDate = Carbon::parse($baseDate)->subDay()->format('Y-m-d');
        } else {
            $finalDate = $baseDate;
        }


        try {

            $imagePath = null;
            if ($request->hasFile('fileInput')) {
                $file = $request->file('fileInput');

                // Paksa simpan ke folder public/jobpending (folder harus sudah ada)
                $destinationPath = public_path('jobpending');

                // buat nama file unik
                $fileName = time() . '_' . $file->getClientOriginalName();

                try {
                    // langsung pindahkan file (tanpa create folder baru)
                    $file->move($destinationPath, $fileName);

                    // path relatif untuk disimpan di DB
                    $imagePath = 'jobpending/' . $fileName;
                } catch (\Exception $e) {
                    // kalau gagal, bisa debug pesan error
                    Log::error('Upload gagal: ' . $e->getMessage());
                }
            }

            $job = JobPending::create([
                'pic' => Auth::user()->id,
                'uuid' => (string) Uuid::uuid4()->toString(),
                'statusenabled' => true,
                'date'   => Carbon::parse($request->date)->format('Y-m-d'),
                'shift_id'  => $request->shift,
                'section_id'  => $request->section,
                'lokasi' => $request->lokasi,
                'issue'  => $request->issue,
                'dibuat' => Auth::user()->nik,
                'verified_dibuat' => Auth::user()->nik,
                'verified_datetime_dibuat' => now(),
                'diterima' => $request->rekan,
                'catatan_verified_diterima' => $request->catatan_verified_diterima,
                'tanggal_pending' => $finalDate,
                'foto' => $imagePath,
            ]);

            // JobPendingNote::create([
            //     'pic' => Auth::user()->id,
            //     'uuid' => (string) Uuid::uuid4()->toString(),
            //     'statusenabled' => true,
            //     'uuid_job'      => $job->uuid,
            //     'catatan' => $request->catatan_diterima,
            // ]);

            // simpan detail aktivitas
            // $listAktivitas = [];
            foreach ($request->aktivitas as $i => $aktivitas) {
                $unit = $request->unit[$i] ?? null;
                $elevasi = $request->elevasi[$i] ?? null;

                // Skip jika semua null / kosong
                if (empty($aktivitas) && empty($unit) && empty($elevasi)) {
                    continue;
                }

                JobPendingDesc::create([
                    'pic'           => Auth::user()->id,
                    'uuid'          => (string) Uuid::uuid4()->toString(),
                    'statusenabled' => true,
                    'uuid_job'      => $job->uuid,
                    'aktivitas'     => $aktivitas,
                    'unit'          => $unit,
                    'elevasi'       => $elevasi,
                ]);

                // kumpulkan untuk pesan WhatsApp
                // $listAktivitas[] = "- {$aktivitas} (Unit: " . ($unit ?? '-') . ", Elevasi: " . ($elevasi ?? '-') . ")";
            }

            // if($request->shift != null){
            //     $shiftDesc = Shift::where('id', $request->shift)->first();
            //     $shiftDesc = $shiftDesc->keterangan;
            // }else{
            //     $shiftDesc = '-';
            // }

            // if($request->rekan != null){
            //     $rekanDesc = User::where('nik', $request->rekan)->first();
            //     $rekanDesc = $rekanDesc->name;
            // }else{
            //     $rekanDesc = '';
            // }

            // $me = User::where('nik', Auth::user()->nik)->first();
            // $me = $me->name;

            // // Data untuk WhatsApp
            // $tanggal = Carbon::parse($request->date)->format('d-m-Y');
            // $shift   = $shiftDesc;
            // $lokasi  = $request->lokasi;
            // $issue   = $request->issue;

            // $text =
            // "Semangat pagi, {$rekanDesc} 🙏\n" .
            // "Dimohon untuk mengecek Aplikasi Daily Foreman terkait *Job Pending ({$me})*.\n\n" .
            // "Berikut laporan pada tanggal {$tanggal}:\n" .
            // "=======================\n" .
            // "JOB PENDING PENGAWAS\n" .
            // "=======================\n" .
            // "Tanggal : {$tanggal}\n" .
            // "Shift   : {$shift}\n" .
            // "Lokasi  : {$lokasi}\n\n" .
            // "Aktivitas/Pekerjaan :\n" .
            // implode("\n", $listAktivitas) . "\n\n" .
            // "Issue :\n{$issue}";

            // $encodedText = urlencode($text);

            // $to = "6285213067944";
            // $session = "mysession";
            // $url = "http://10.10.2.6:5001/message/send-text?session={$session}&to={$to}&text={$encodedText}";

            // Http::get($url);

            DB::commit();
            return redirect()->route('jobpending')->with('success', 'Job Pending berhasil dikirim');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('info', $th->getMessage());
        }
    }

    public function excelDetail()
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
        return Excel::download(new JobPendingExport($startTimeFormatted, $endTimeFormatted), 'Laporan Job Pending.xlsx');
    }


    public function show($uuid)
    {
        $data = DB::table('JOB_PENDING as jp')
        ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
        ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
        ->leftJoin('users as us2', 'jp.dibuat', '=', 'us2.nik')
        ->leftJoin('users as us3', 'jp.diterima', '=', 'us3.nik')
        ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
        ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
        ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
        ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
        ->select(
            'jp.id',
            'jp.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'jp.statusenabled',
            'sh.keterangan as shift',
            'sec.keterangan as section',
            'jp.date',
            'jp.tanggal_pending',
            'jp.lokasi',
            'jd.aktivitas',
            'jd.unit',
            'jd.elevasi',
            'jd.done',
            'jp.issue',
            'jp.foto',
            'jp.dibuat as nik_dibuat',
            'db.name as nama_dibuat',
            'us2.role as jabatan_dibuat',
            'jp.diterima as nik_diterima',
            'dt.name as nama_diterima',
            'us2.role as jabatan_diterima',
            'jp.verified_dibuat',
            'jp.verified_diterima',
            'jp.catatan_verified_diterima',
        )->where('jp.statusenabled', true)->where('jp.uuid', $uuid)->get();
        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $data->first();

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_dibuat != null) {
                $fileName = 'verified_dibuat' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_dibuat)]), $filePath);

                $item->verified_dibuat_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_dibuat_qr = null;
            }

            if ($item->verified_diterima != null) {
                $fileName = 'verified_diterima' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_diterima)]), $filePath);

                $item->verified_diterima_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_diterima_qr = null;
            }
        }

        // dd($data);

        return view('job-pending.show', compact('data'));

    }

    public function cetak($uuid)
    {
        $data = DB::table('JOB_PENDING as jp')
        ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
        ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
        ->leftJoin('users as us2', 'jp.dibuat', '=', 'us2.nik')
        ->leftJoin('users as us3', 'jp.diterima', '=', 'us3.nik')
        ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
        ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
        ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
        ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
        ->select(
            'jp.id',
            'jp.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'jp.statusenabled',
            'sh.keterangan as shift',
            'sec.keterangan as section',
            'jp.date',
            'jp.tanggal_pending',
            'jp.lokasi',
            'jd.aktivitas',
            'jd.unit',
            'jd.elevasi',
            'jd.done',
            'jp.issue',
            'jp.foto',
            'jp.dibuat as nik_dibuat',
            'db.name as nama_dibuat',
            'us2.role as jabatan_dibuat',
            'jp.diterima as nik_diterima',
            'dt.name as nama_diterima',
            'us2.role as jabatan_diterima',
            'jp.verified_dibuat',
            'jp.verified_diterima',
            'jp.catatan_verified_diterima',
        )->where('jp.statusenabled', true)->where('jp.uuid', $uuid)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $data->first();

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_dibuat != null) {
                $fileName = 'verified_dibuat' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_dibuat)]), $filePath);

                $item->verified_dibuat_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_dibuat_qr = null;
            }

            if ($item->verified_diterima != null) {
                $fileName = 'verified_diterima' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_diterima)]), $filePath);

                $item->verified_diterima_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_diterima_qr = null;
            }
        }



        return view('job-pending.cetak', compact('data'));

    }

    public function detail()
    {
        $data = DB::table('JOB_PENDING as jp')
        ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
        ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
        ->leftJoin('users as us2', 'jp.dibuat', '=', 'us2.nik')
        ->leftJoin('users as us3', 'jp.diterima', '=', 'us3.nik')
        ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
        ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
        ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
        ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
        ->select(
            'jp.id',
            'jp.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'jp.statusenabled',
            'sh.keterangan as shift',
            'sec.keterangan as section',
            'jp.date',
            'jp.tanggal_pending',
            'jp.lokasi',
            'jd.aktivitas',
            'jd.unit',
            'jd.elevasi',
            'jd.done',
            'jp.issue',
            'jp.dibuat as nik_dibuat',
            'db.name as nama_dibuat',
            'us2.role as jabatan_dibuat',
            'jp.diterima as nik_diterima',
            'dt.name as nama_diterima',
            'us2.role as jabatan_diterima',
            'jp.verified_dibuat',
            'jp.verified_diterima',
        )->where('jp.statusenabled', true)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $data->first();

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_dibuat != null) {
                $fileName = 'verified_dibuat' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_dibuat)]), $filePath);

                $item->verified_dibuat = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_dibuat = null;
            }

            if ($item->verified_diterima != null) {
                $fileName = 'verified_diterima' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)
                    ->format('png')
                    ->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_diterima)]), $filePath);

                $item->verified_diterima = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_diterima = null;
            }
        }


        return view('job-pending.detail', compact('data'));

    }

    public function catatanPenerima(Request $request, $uuid)
    {
        $job =  JobPending::where('uuid', $uuid)->first();

        try {
            JobPending::where('uuid', $job->uuid)->update([
                'catatan_verified_diterima' => $request->catatan_verified_diterima,
            ]);

            return redirect()->back()->with('success', 'Berhasil mengirim catatan');

        } catch (\Throwable $th) {
            return redirect()->back()->with('info', nl2br('Gagal mengirim catatan..\n' . $th->getMessage()));
        }
    }

    public function apiDetail(Request $request)
    {
        // Range tanggal
        if (empty($request->rangeStart) || empty($request->rangeEnd)) {
            $time = new DateTime();
            $startDate = $time->format('Y-m-d');
            $endDate   = $time->format('Y-m-d');
        } else {
            $startDate = (new DateTime($request->rangeStart))->format('Y-m-d');
            $endDate   = (new DateTime($request->rangeEnd))->format('Y-m-d');
        }

        // Ambil parameter pagination (hindari konflik dengan variabel date)
        $offset = $request->input('start', 0);   // Offset
        $length = $request->input('length', 10); // Default 10 item per halaman
        $draw   = $request->input('draw');

        // Base query (jangan ->get() dulu)
        $supportQuery = DB::table('JOB_PENDING as jp')
            ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
            ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
            ->leftJoin('users as us2', 'jp.dibuat', '=', 'us2.nik')
            ->leftJoin('users as us3', 'jp.diterima', '=', 'us3.nik')
            ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
            ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
            ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
            ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
            ->select(
                'jp.id',
                'jp.uuid',
                'us.name as pic',
                'us.nik as nik_pic',
                'jp.statusenabled',
                'sh.keterangan as shift',
                'sec.keterangan as section',
                'jp.date',
                'jp.tanggal_pending',
                'jp.lokasi',
                'jd.aktivitas',
                'jd.unit',
                'jd.elevasi',
                'jd.done',
                'jp.issue',
                'jp.dibuat as nik_dibuat',
                'db.name as nama_dibuat',
                'us2.role as jabatan_dibuat',
                'jp.diterima as nik_diterima',
                'dt.name as nama_diterima',
                'us2.role as jabatan_diterima',
                'jp.verified_dibuat',
                'jp.verified_diterima',
            )
            ->where('jp.statusenabled', true)
            ->whereBetween(DB::raw('CAST(jp.date as DATE)'), [$startDate, $endDate]);

        // Search filter
        if ($request->search['value']) {
            $searchValue = '%' . $request->search['value'] . '%';

            $columnsToSearch = [
                'db.name', 'dt.name'
            ];

            $supportQuery->where(function($query) use ($columnsToSearch, $searchValue) {
                foreach ($columnsToSearch as $column) {
                    $query->orWhere($column, 'like', $searchValue);
                }
            });
        }

        // Count sebelum pagination
        $filteredRecords = $supportQuery->count();

        // Pagination
        $support = $supportQuery->skip($offset)->take($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $filteredRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $support
        ]);


    }

    public function download($uuid)
    {
        $data = DB::table('JOB_PENDING as jp')
        ->leftJoin('JOB_PENDING_DESC as jd', 'jp.uuid', '=', 'jd.uuid_job')
        ->leftJoin('users as us', 'jp.pic', '=', 'us.id')
        ->leftJoin('users as us2', 'jp.dibuat', '=', 'us2.nik')
        ->leftJoin('users as us3', 'jp.diterima', '=', 'us3.nik')
        ->leftJoin('REF_SHIFT as sh', 'jp.shift_id', 'sh.id')
        ->leftJoin('REF_SECTION as sec', 'jp.section_id', 'sec.id')
        ->leftJoin('users as db', 'jp.dibuat', '=', 'db.nik')
        ->leftJoin('users as dt', 'jp.diterima', '=', 'dt.nik')
        ->select(
            'jp.id',
            'jp.uuid',
            'us.name as pic',
            'us.nik as nik_pic',
            'jp.statusenabled',
            'sh.keterangan as shift',
            'sec.keterangan as section',
            'jp.date',
            'jp.tanggal_pending',
            'jp.lokasi',
            'jd.aktivitas',
            'jd.unit',
            'jd.elevasi',
            'jd.done',
            'jp.issue',
            'jp.foto',
            'jp.dibuat as nik_dibuat',
            'db.name as nama_dibuat',
            'us2.role as jabatan_dibuat',
            'jp.diterima as nik_diterima',
            'dt.name as nama_diterima',
            'us2.role as jabatan_diterima',
            'jp.verified_dibuat',
            'jp.verified_diterima',
            'jp.catatan_verified_diterima',
        )->where('jp.statusenabled', true)->where('jp.uuid', $uuid)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('info', 'Maaf, data tidak ditemukan');
        }else {
            $item = $data->first();

            $qrTempFolder = storage_path('app/public/qr-temp');
            if (!File::exists($qrTempFolder)) {
                File::makeDirectory($qrTempFolder, 0755, true);
            }

            if ($item->verified_dibuat != null) {
                $fileName = 'verified_dibuat' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_dibuat)]), $filePath);
                $item->verified_dibuat_qr = $filePath;

                $item->verified_dibuat_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_dibuat_qr = null;
            }

            if ($item->verified_diterima != null) {
                $fileName = 'verified_diterima' . $item->uuid . '.png';
                $filePath = $qrTempFolder . DIRECTORY_SEPARATOR . $fileName;

                QrCode::size(150)->format('png')->generate(route('verified.index', ['encodedNik' => base64_encode($item->verified_diterima)]), $filePath);
                $item->verified_diterima_qr = $filePath;

                $item->verified_diterima_qr = asset('storage/qr-temp/' . $fileName);
            } else {
                $item->verified_diterima_qr = null;
            }
        }
        $pdf = PDF::loadView('job-pending.download', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('Job Pending Pengawas ('. $data[0]->date. '-'.$data[0]->nama_dibuat .'-'.$data[0]->shift .').pdf');

    }

    public function verifikasi($uuid)
    {
        try {
            DB::beginTransaction();

            // Ambil job
            $job = JobPending::where('uuid', $uuid)->first();

            // Cek apakah job ada
            if (!$job) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data job pending tidak ditemukan'
                ], 404);
            }

            // Cek apakah sudah diverifikasi
            if ($job->verified_diterima != null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, Job Pending ini sudah diverifikasi'
                ], 400);
            }

            // Lakukan verifikasi
            $job->verified_diterima = Auth::user()->nik;
            $job->diterima = Auth::user()->nik;
            $job->verified_datetime_diterima = now();
            $job->done = true;
            $job->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Job Pending berhasil diverifikasi'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

}
