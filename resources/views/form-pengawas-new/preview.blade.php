@include('layout.head', ['title' => 'Laporan Kerja Pengawas Produksi'])
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
        .action-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .action-btn {
            width: 100%;
            padding: 12px;
            font-size: 15px;
        }

        .verifikasi-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .verifikasi-btn {
            width: 100%;
            text-align: center;
            font-size: 15px;
            padding: 12px;
        }
    }

    @media (max-width: 576px) {
        .col-12 img {
            max-width: 150px;
        }
    }

    .alat-support-table {
        min-width: 1300px;
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
        width: 50px;
    }

    .alat-support-table th:nth-child(3) {
        width: 120px;
    }

    .alat-support-table th:nth-child(4) {
        width: 90px;
    }

    .alat-support-table th:nth-child(5) {
        width: 80px;
    }

    .alat-support-table th:nth-child(6) {
        width: 60px;
    }

    .alat-support-table th:nth-child(7) {
        width: 80px;
    }

    .alat-support-table th:nth-child(8) {
        width: 120px;
    }

    .alat-support-table th:nth-child(9) {
        width: 150px;
    }

    table {
        -fs-table-paginate: paginate;
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
        margin-bottom: 2px;
    }

    .info-table td {
        padding: 5px;
    }

    .catatan-table td {
        border-bottom: 1px solid #000;
        padding: 6px 4px;
        font-size: 12px;
    }

    .tanda-tangan-table img {
        max-width: 70px;
    }

    .verifikasi-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
    }

    .verifikasi-btn {
        display: inline-block;
        background-color: #198754;
        color: #fff;
        font-size: 14px;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        white-space: nowrap;
    }

    .verifikasi-btn:hover {
        background-color: #157347;
        color: #fff;
    }

    .action-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-end;
        margin-top: 12px;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 14px;
        font-size: 14px;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        white-space: nowrap;
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
                                        <img
                                            src="{{ asset('dashboard/assets') }}/images/logo-full.png"
                                            class="img-fluid mb-2"
                                            alt="Logo"
                                            style="max-width: 200px;">
                                    </div>
                                    <div class="col-12 col-sm-6 text-sm-end">
                                        <h6 class="mb-0">FM-PRD-03/03/06/02/24</h6>
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-center">
                                <u>LAPORAN HARIAN FOREMAN PRODUKSI</u>
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
                                        <td colspan="14">Unit Kerja</td>
                                        <td>:</td>
                                        <td>{{ $data['daily']->lokasi }}</td>
                                        <td colspan="7"></td>
                                        <td colspan="3">Nama Supervisor</td>
                                        <td>:</td>
                                        <td colspan="7">{{ $data['daily']->nama_supervisor }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="14">Jam Kerja</td>
                                        <td>:</td>
                                        <td>
                                            {{ $data['daily']->shift == 'Siang'
                                                ? '06:30 - 18:30'
                                                : '18:30 - 06:30' }}
                                        </td>
                                        <td colspan="7"></td>
                                        <td colspan="3"></td>
                                        <td></td>
                                        <td colspan="7"></td>
                                    </tr>
                                </table>
                            </div>

                            <h4>A. FRONT LOADING</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th rowspan="3">Brand</th>
                                            <th rowspan="3">Type</th>
                                            <th rowspan="3">No Unit</th>
                                            <th>Shift</th>
                                            <th colspan="12">Jam</th>
                                        </tr>

                                        @if ($data['daily']->shift == 'Siang')
                                            <tr>
                                                <th>Siang</th>
                                                @foreach ([
                                                    '07-08','08-09','09-10','10-11',
                                                    '11-12','12-13','13-14','14-15',
                                                    '15-16','16-17','17-18','18-19'
                                                ] as $slot)
                                                    <th>{{ $slot }}</th>
                                                @endforeach
                                            </tr>
                                        @else
                                            <tr>
                                                <th>Malam</th>
                                                @foreach ([
                                                    '19-20','20-21','21-22','22-23',
                                                    '23-24','24-01','01-02','02-03',
                                                    '03-04','04-05','05-06','06-07'
                                                ] as $slot)
                                                    <th>{{ $slot }}</th>
                                                @endforeach
                                            </tr>
                                        @endif
                                    </thead>

                                    <tbody>
                                        @foreach ($data['front'] as $brand => $units)
                                            @php($groupedByType = $units->groupBy('type'))

                                            @foreach ($groupedByType as $type => $typeUnits)
                                                @foreach ($typeUnits as $index => $unit)
                                                    <tr>
                                                        @if ($loop->parent->first && $index === 0)
                                                            <td rowspan="{{ $units->count() }}">{{ $brand }}</td>
                                                        @endif

                                                        @if ($index === 0)
                                                            <td rowspan="{{ $typeUnits->count() }}">{{ $type }}</td>
                                                        @endif

                                                        <td colspan="2">{{ $unit['nomor_unit'] }}</td>

                                                        @foreach ($unit['siang'] as $slot)
                                                            <td>{{ $slot->status }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h4>B. ALAT SUPPORT</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm alat-support-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">No. Unit</th>
                                            <th rowspan="2">Nama Operator</th>
                                            <th rowspan="2">Tanggal</th>
                                            <th colspan="2">HM Unit</th>
                                            <th rowspan="2">Total</th>
                                            <th rowspan="2">Cash Pengawas</th>
                                            <th rowspan="2">Ket.</th>
                                        </tr>
                                        <tr>
                                            <th>Awal</th>
                                            <th>Akhir</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($data['support'] as $sp)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sp->nomor_unit }}</td>
                                                <td>{{ $sp->nama_operator }}</td>
                                                <td>{{ date('d-m-Y', strtotime($sp->tanggal)) }}</td>
                                                <td>{{ $sp->hm_awal }}</td>
                                                <td>{{ $sp->hm_akhir }}</td>
                                                <td>{{ number_format($sp->hm_akhir - $sp->hm_awal, 2) }}</td>
                                                <td>{{ $sp->hm_cash }}</td>
                                                <td>{{ $sp->keterangan }}</td>
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
                                                @foreach ($data['catatan'] as $cp)
                                                    <tr>
                                                        <td>
                                                            @if ($cp->jam_start && $cp->jam_stop)
                                                                ({{ \Carbon\Carbon::parse($cp->jam_start)->format('H:i') }} -
                                                                {{ \Carbon\Carbon::parse($cp->jam_stop)->format('H:i') }})
                                                            @endif
                                                            {{ $cp->keterangan }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @foreach ($data['front'] as $brand => $units)
                                                    @foreach ($units as $unit)
                                                        @if ($data['daily']->shift == 'Siang')
                                                            @foreach ($unit['siang'] as $index => $slot)
                                                                @if ($slot->keterangan != '')
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $unit['nomor_unit'] }}</strong>
                                                                            ({{ $timeSlots['siang'][$index] }}) —
                                                                            {{ $slot->keterangan }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach ($unit['malam'] as $index => $slot)
                                                                @if ($slot->keterangan != '')
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $unit['nomor_unit'] }}</strong>
                                                                            ({{ $timeSlots['malam'][$index] }}) —
                                                                            {{ $slot->keterangan }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endforeach
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
                                                        <div>
                                                            {{ $data['daily']->nama_supervisor ?? $data['daily']->nama_superintendent }}
                                                        </div>
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

                        </div>
                        <div class="card-body p-3">
                            <div class="verifikasi-actions">

                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="{{ route('form-pengawas-new.verified.all', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Semua
                                    </a>
                                    <a href="{{ route('form-pengawas-new.verified.foreman', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Foreman
                                    </a>
                                    <a href="{{ route('form-pengawas-new.verified.supervisor', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Supervisor
                                    </a>
                                    <a href="{{ route('form-pengawas-new.verified.superintendent', $data['daily']->uuid) }}" class="verifikasi-btn">
                                        Verifikasi Superintendent
                                    </a>
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

                        </div>
                        <div class="action-actions">
                            <a href="#" onclick="window.history.back()" class="action-btn action-secondary">
                                Kembali
                            </a>
                            <a href="#" class="action-btn action-danger" data-bs-toggle="modal" data-bs-target="#deleteLaporanKerja{{ $data['daily']->uuid }}">
                                Hapus
                            </a>
                            <div class="modal fade" id="deleteLaporanKerja{{ $data['daily']->uuid }}" aria-hidden="true" aria-labelledby="..." tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <lord-icon
                                                src="/tdrtiskw.json"
                                                trigger="loop"
                                                colors="primary:#f7b84b,secondary:#405189"
                                                style="width:130px;height:130px">
                                            </lord-icon>
                                            <div class="mt-4 pt-4">
                                                <h4>Yakin menghapus Laporan Kerja ini?</h4>
                                                <p class="text-muted"> Data yang dihapus tidak ditampilkan kembali</p>
                                                <!-- Toogle to second dialog -->
                                                <a href="{{ route('form-pengawas-new.delete', $data['daily']->uuid) }}"><span class="badge bg-danger" style="font-size:14px">Hapus</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <a href="{{ route('form-pengawas-new.pdf', $data['daily']->uuid) }}"
                            target="_blank"
                            class="action-btn action-primary">
                                Download PDF
                            </a>

                            <a href="{{ route('form-pengawas-new.download', $data['daily']->uuid) }}"
                            target="_blank"
                            class="action-btn action-outline">
                                Print
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
