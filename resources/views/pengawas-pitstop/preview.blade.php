@include('layout.head', ['title' => 'Laporan Kerja Pengawas Pitstop'])
@include('layout.sidebar')
@include('layout.header')

<style>
    .table-responsive table {
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        table {
            font-size: 11px;
        }

        table th,
        table td {
            padding: 6px;
        }
    }

    @media (max-width: 576px) {
        .col-12 img {
            max-width: 150px;
        }
    }

    table {
        -fs-table-paginate: paginate;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    table tr td,
    table tr th {
        font-size: small;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }

    .info-table td {
        padding: 5px;
    }

    table.data_table {
        width: 100%;
        border: 1px solid #000;
        table-layout: fixed;
    }

    table.data_table td,
    table.data_table th {
        border: 1px solid #000;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .catatan-table td {
        border-bottom: 1px solid #000;
        padding: 6px 4px;
        font-size: 12px;
    }

    .tanda-tangan-table img {
        max-width: 70px;
    }

    .verifikasi-actions,
    .action-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
        margin-top: 12px;
    }

    .verifikasi-btn,
    .action-btn {
        display: inline-block;
        padding: 8px 14px;
        font-size: 14px;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
    }

    .verifikasi-btn {
        background-color: #198754;
        color: #fff;
    }

    .action-primary {
        background-color: #0d6efd;
        color: #fff;
    }

    .action-primary:hover {
        background-color: #0b5ed7;
        color: #fff;
    }

    .action-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .action-secondary:hover {
        background-color: #5c636a;
        color: #fff;
    }

    .action-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .action-danger:hover {
        background-color: #c01a2b;
        color: #fff;
    }

    .action-outline {
        border: 1px solid #0d6efd;
        color: #0d6efd;
        background-color: #fff;
    }

    .action-outline:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .table-responsive-horizontal {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-alat-support {
        min-width: 1400px;
        table-layout: fixed;
    }

    .table-alat-support th,
    .table-alat-support td {
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        font-size: 12px;
    }

    .table-scroll-x {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .alat-support-table {
        min-width: 1800px;
        table-layout: fixed;
    }

    .alat-support-table th,
    .alat-support-table td {
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        padding: 10px;
        font-size: 11px;
    }

    .alat-support-table th:nth-child(1),
    .alat-support-table td:nth-child(1) {
        width: 40px;
    }

    .alat-support-table th:nth-child(2) {
        width: 70px;
    }

    .alat-support-table th:nth-child(3) {
        width: 100px;
    }

    .alat-support-table th:nth-child(4) {
        width: 90px;
    }

    .alat-support-table th:nth-child(5) {
        width: 180px;
    }

    .alat-support-table th:nth-child(6) {
        width: 300px;
    }

    .alat-support-table th:nth-child(7) {
        width: 180px;
    }

    .alat-support-table th:nth-child(8) {
        width: 320px;
    }

    .alat-support-table th:nth-child(9) {
        width: 150px;
    }

    @media (max-width: 768px) {
        .verifikasi-actions,
        .action-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .verifikasi-btn,
        .action-btn {
            width: 100%;
            padding: 12px;
            font-size: 15px;
        }
    }
</style>

<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <div class="row align-items-center g-2 text-center text-sm-start">
                                    <div class="col-12 col-sm-6">
                                        <img src="{{ asset('dashboard/assets') }}/images/logo-full.png" class="img-fluid mb-2" alt="Logo" style="max-width:200px">
                                    </div>
                                    <div class="col-12 col-sm-6 text-sm-end">
                                        <h6 class="mb-0">FM-PRD-68/00/04/10/23</h6>
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-center">
                                <u>LAPORAN HARIAN PENGAWAS PITSTOP</u>
                            </h2>

                            <div class="table-responsive">
                                <table class="info-table">
                                    <tr>
                                        <td colspan="14">Tanggal</td>
                                        <td>:</td>
                                        <td>{{ date('d-m-Y', strtotime($data['daily']->tanggal)) }}</td>
                                        <td colspan="7"></td>
                                        <td colspan="3">Nama Foreman</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $data['daily']->nama_foreman }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="14">Shift</td>
                                        <td>:</td>
                                        <td>{{ $data['daily']->shift }}</td>
                                        <td colspan="7"></td>
                                        <td colspan="3">NIK Foreman</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $data['daily']->nik_foreman }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="14">Lokasi</td>
                                        <td>:</td>
                                        <td>{{ $data['daily']->area }}</td>
                                        <td colspan="7"></td>
                                        <td colspan="3">Nama Supervisor</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $data['daily']->nama_supervisor }}</td>
                                    </tr>
                                </table>
                            </div>

                            <h4>B. ALAT SUPPORT</h4>

                            <div class="table-scroll-x">
                                <table class="data_table alat-support-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="width:40px;">No</th>
                                            <th rowspan="2">Jenis Unit</th>
                                            <th rowspan="2">Type Unit</th>
                                            <th rowspan="2">No. Unit</th>
                                            <th rowspan="2">Operator (Settingan)</th>
                                            <th colspan="4">Status</th>
                                            <th rowspan="2">Operator (Ready)</th>
                                            <th rowspan="2">Ket.</th>
                                        </tr>
                                        <tr>
                                            <th>Unit Breakdown</th>
                                            <th>Unit Ready</th>
                                            <th>Operator Ready</th>
                                            <th>Durasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['dailyDesc'] as $sp)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sp->jenis_unit }}</td>
                                                <td>{{ $sp->type_unit }}</td>
                                                <td>{{ $sp->no_unit }}</td>
                                                <td style="text-align:left; {{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                                                    {{ $sp->opr_settingan }}-{{ $sp->nama_opr_settingan }}
                                                </td>
                                                <td>{!! $sp->isOutsideShift ? '<b>'.$sp->time_breakdown.'</b>' : $sp->time_breakdown !!}</td>
                                                <td>{{ $sp->status_unit_ready_fmt }}</td>
                                                <td>{{ $sp->status_opr_ready_fmt }}</td>
                                                <td style="{{ $sp->totalMinutes > 30 ? 'color:red;font-weight:bold;' : '' }}">
                                                    {{ $sp->durasi_eff }}
                                                </td>
                                                <td style="text-align:left; {{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                                                    {{ $sp->opr_ready }}-{{ $sp->nama_opr_ready }}
                                                </td>
                                                <td style="text-align:left;">
                                                    {!! $sp->isOutsideShift ? '<b>'.$sp->keterangan.'</b>' : $sp->keterangan !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <div class="row g-3 mt-2">
                                <div class="col-12 col-lg-8">
                                    <div class="table-responsive">
                                        <table class="table table-borderless catatan-table">
                                            <tbody>
                                                <tr>
                                                    <td>{!! nl2br(e($data['daily']->catatan_pengawas)) !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center tanda-tangan-table">
                                            <thead>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <th>Diperiksa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        {!! $data['daily']->verified_foreman !!}
                                                        <div>{{ $data['daily']->nama_foreman }}</div>
                                                    </td>
                                                    <td>
                                                        {!! $data['daily']->verified_supervisor ?? $data['daily']->verified_superintendent !!}
                                                        <div>{{ $data['daily']->nama_supervisor ?? $data['daily']->nama_superintendent }}</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Foreman</th>
                                                    <th>SV / SI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="verifikasi-actions">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="{{ route('pengawas-pitstop.verified.all', $data['daily']->uuid) }}" class="verifikasi-btn">Verifikasi Semua</a>
                                    <a href="{{ route('pengawas-pitstop.verified.foreman', $data['daily']->uuid) }}" class="verifikasi-btn">Verifikasi Foreman</a>
                                    <a href="{{ route('pengawas-pitstop.verified.supervisor', $data['daily']->uuid) }}" class="verifikasi-btn">Verifikasi Supervisor</a>
                                    <a href="{{ route('pengawas-pitstop.verified.superintendent', $data['daily']->uuid) }}" class="verifikasi-btn">Verifikasi Superintendent</a>
                                @endif
                                 @if (Auth::user()->nik == $data['daily']->nik_foreman && $data['daily']->verified_foreman == null)
                                    <a href="{{ route('form-pengawas-new.verified.foreman', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Foreman
                                    </a>
                                @endif

                                @if (Auth::user()->nik == $data['daily']->nik_supervisor && $data['daily']->verified_supervisor == null)
                                    <a href="{{ route('form-pengawas-new.verified.supervisor', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Supervisor
                                    </a>
                                @endif

                                @if (Auth::user()->nik == $data['daily']->nik_superintendent && $data['daily']->verified_superintendent == null)
                                    <a href="{{ route('form-pengawas-new.verified.superintendent', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Superintendent
                                    </a>
                                @endif
                            </div>


                            <div class="action-actions">
                                <a href="#" onclick="window.history.back()" class="action-btn action-secondary">Kembali</a>
                                <a href="#" class="action-btn action-danger" data-bs-toggle="modal" data-bs-target="#deleteLaporanKerja{{ $data['daily']->uuid }}">Hapus</a>
                                <a href="{{ route('pengawas-pitstop.download', $data['daily']->uuid) }}" target="_blank" class="action-btn action-primary">Download</a>
                                <a href="{{ route('pengawas-pitstop.cetak', $data['daily']->uuid) }}" target="_blank" class="action-btn action-outline">Print</a>
                            </div>

                            @include('pengawas-pitstop.delete-preview')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
