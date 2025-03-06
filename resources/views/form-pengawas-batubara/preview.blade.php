@include('layout.head', ['title' => 'Preview Laporan Kerja Batu Bara'])
@include('layout.sidebar')
@include('layout.header')
<style>

    /* body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    } */

    table{
        page-break-inside:auto;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    }
    table {
        -fs-table-paginate: paginate;
    }
    tr{
        page-break-inside:avoid;
        page-break-after:auto;
    }

    table tr td, table tr th{
        font-size: small;
    }
    .header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        border-bottom: 2px solid #000;
        padding: .3rem;
    }

    .header img {
        vertical-align: middle;
    }

    .header .title {
        display: inline-block;
        margin-left: 10px;
        text-align: left;
    }

    .header .title h1 {
        margin: 0;
        font-size: 18px;
        color: #0000FF;
    }

    .header .title p {
        margin: 0;
        font-size: 12px;
    }

    .header .doc-number {
        text-align: right;
        font-size: 12px;
    }

    .info-table,
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2px;
    }
    table.inf-table{
        border:none;
    }

    .info-table td {
        padding: 5px;
        width: 20pt;
    }

    .info-table td:first-child {
        width: 15%;
    }

    .info-table td:nth-child(2) {
        width: .2%;
        /* border-bottom: 1px dotted #000; */
    }

    .info-table td:nth-child(3) {
        width: 30%;
        vertical-align: bottom;
    }

    .info-table td:nth-child(4) {
        width: 10%;
        background-color: rgb(255, 255, 255);
        /* border-bottom: 1px dotted #000; */
    }
    .info-table td:nth-child(5) {
        width: 15%;
        vertical-align: bottom;
    }
    .info-table td:nth-child(6) {
        width: .2%;
        /* border-bottom: 1px dotted #000; */
    }
    .info-table td:nth-child(7) {
        width: 30%;
        /* border-bottom: 1px dotted #000; */
    }
    .data-table th,
    .data-table td {
        border: 1px solid #000;
        text-align: center;
    }

    .flex {
        display: flex;
    }
    table.data_table{
        width: 100%;
        border: 1px solid #000;
        table-layout: fixed;
    }
    table.data_table tr td, table.data_table tr th{
        text-align: center;
        border:1px solid #000;
    }
    table.data_table tbody tr td{
        height: 15pt;
    }

    table.table_close{
        width: 100%;
        /* border: 1px solid #000; */
        table-layout: fixed;
    }
    table.table_close tr td, table.table_close tr th{
        /* border:1px solid #000; */
    }
    table.table_close tr th{
        height: 15pt;
        padding:.2rem;
    }
    th.noborder{
        border:none;
        /* border-bottom: none; */
    }
    hr{
        margin-bottom:1rem;
    }
    .flex{
        display: flex;
        justify-content: space-between;
    }
    .hor{
        display: flex;
        flex-direction: column;
    }
    h4{
        margin-bottom: 0px;
    }
    .grid-container {
        display: grid;
        grid-template-columns: 70% 30%;
        gap: 20px;
        margin: 20px;
    }

    .grid-table table {
        width: 80%;
        border-collapse: collapse;
    }

    .grid-table th, .grid-table td {
        border: 1px solid #000;
        /* padding: 8px; */
        text-align: center;
    }

    .grid-table th {
        background-color: #f4f4f4;
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
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-2"><img
                                                src="{{ asset('dashboard/assets') }}/images/logo-full.png" class="img-fluid" alt="images" width="200px">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <h6>FM-PRD-54/00/26/08/24</h6>
                                    </div>
                                </div>
                            </div>
                            <h2 style="text-align: center;"><u>LAPORAN HARIAN PENGAWAS BATUBARA</u></h2>
                            <table class="info-table">
                                <tr>
                                    <td colspan="14">Tanggal</td>
                                    <td>:</td>
                                    <td>{{ date('d-m-Y', strtotime($data['daily']->tanggal)) }}</td>
                                    <td colspan="7"></td>
                                    <td colspan="3">Nama</td>
                                    <td>:</td>
                                    <td colspan="7">{{ $data['daily']->pic }}</td>
                                </tr>
                                <tr>
                                    <td colspan="14">Shift</td>
                                    <td>:</td>
                                    <td>{{ $data['daily']->shift }}</td>
                                    <td colspan="7"></td>
                                    <td colspan="3">NIK</td>
                                    <td>:</td>
                                    <td colspan="7">{{ $data['daily']->nik_pic }}</td>
                                </tr>
                                <tr>
                                    <td colspan="14">Jam Kerja</td>
                                    <td>:</td>
                                    <td>{{ $data['daily']->shift == 'Siang' ? '06:30 - 18:30' : '18:30 - 06:30' }}</td>
                                    <td colspan="7"></td>
                                    <td colspan="3"></td>
                                    <td></td>
                                    <td colspan="7"></td>
                                </tr>
                            </table>
                            <h4>
                                A. LOADING POINT
                            </h4>
                            <table class="data_table">
                                <thead>
                                    <tr>
                                        <th>Subcont</th>
                                        <th>PIT</th>
                                        <th>Nama Pengawas</th>
                                        <th>Fleet EX</th>
                                        <th>Jumlah DT</th>
                                        <th>Seam BB</th>
                                        <th>Jarak (km)</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['loading'] as $lp)
                                    <tr>
                                        <td style="padding-left:2px;">{{ $lp->subcont }}</td>
                                        <td style="padding-left:2px;">{{ $lp->pit }}</td>
                                        <td style="padding-left:2px;">{{ $lp->pengawas }}</td>
                                        <td style="padding-left:2px;">{{ $lp->fleet_ex }}</td>
                                        <td style="text-align: center">{{ $lp->jumlah_dt }}</td>
                                        <td style="text-align: center">{{ $lp->seam_bb }}</td>
                                        <td style="text-align: center">{{ $lp->jarak }}</td>
                                        <td style="padding-left:2px;">{{ $lp->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                    @if ($data['loading']->isEmpty())
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="text-align: center"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <h4>
                                B.  UNIT SUPPORT
                            </h4>
                            <table class="data_table">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Subcont</th>
                                        <th>No. Unit</th>
                                        <th>Area / Jalan</th>
                                        <th>Status / Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['support'] as $sp)
                                    <tr>
                                        <td style="padding-left:2px;">{{ $sp->jenis }}</td>
                                        <td style="padding-left:2px;">{{ $sp->subcont }}</td>
                                        <td style="padding-left:2px;">{{ $sp->nomor_unit }}</td>
                                        <td style="padding-left:2px;">{{ $sp->area }}</td>
                                        <td style="padding-left:2px;">{{ $sp->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                    @if ($data['support']->isEmpty())
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                            <td style="padding-left:2px;"></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <br>
                            <div style="font-size: 8pt;"><i>KET:</i></div>
                            <div class="grid-container">
                                <div class="grid-table">
                                    <table >
                                        <tbody>
                                            @foreach ($data['catatan'] as $cp)
                                            <tr>
                                                <td style="border: none; border-bottom: 1px solid black; text-align:left; padding-top:7px;">
                                                    @if($cp->jam_start && $cp->jam_stop)
                                                        ({{ \Carbon\Carbon::parse($cp->jam_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($cp->jam_stop)->format('H:i') }})
                                                    @endif
                                                    {{ $cp->keterangan }}
                                                </td>
                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>

                                <!-- Grid kedua: Tabel -->
                                <div class="grid-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Dibuat</th>
                                                <th>Diperiksa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">{!! $data['daily']->verified_foreman !!}
                                                    <br>
                                                    @if ($data['daily']->verified_foreman != null)
                                                        {{ $data['daily']->nama_foreman }}
                                                    @endif
                                                </td>
                                                <td style="padding-top: 5px;padding-bottom: 5px;">
                                                    @if ($data['daily']->verified_supervisor != null)
                                                        {!! $data['daily']->verified_supervisor !!}
                                                        <br>
                                                        {{ $data['daily']->nama_supervisor }}
                                                    @elseif ($data['daily']->verified_superintendent != null)
                                                        {!! $data['daily']->verified_superintendent !!}
                                                        <br>
                                                        {{ $data['daily']->nama_superintendent }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th>Foreman</th>
                                                <th>SV/SI</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="{{ route('form-pengawas-batubara.verified.all', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="{{ route('form-pengawas-batubara.verified.foreman', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="{{ route('form-pengawas-batubara.verified.supervisor', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="{{ route('form-pengawas-batubara.verified.superintendent', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $data['daily']->nik_foreman && $data['daily']->verified_foreman == null)
                                    <a href="{{ route('form-pengawas-batubara.verified.foreman', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $data['daily']->nik_supervisor && $data['daily']->verified_supervisor == null)
                                    <a href="{{ route('form-pengawas-batubara.verified.supervisor', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $data['daily']->nik_superintendent && $data['daily']->verified_superintendent == null)
                                    <a href="{{ route('form-pengawas-batubara.verified.superintendent', $data['daily']->uuid) }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('form-pengawas-batubara.show') }}" class="avtar avtar-s btn-link-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><defs><path id="stashArrowReplyDuotone0" fill="currentColor" d="M10.296 6.889L4.833 11.18a.5.5 0 0 0 0 .786l5.463 4.292a.5.5 0 0 0 .801-.482l-.355-1.955c5.016-1.204 7.108 1.494 7.914 3.235c.118.254.614.205.64-.073c.645-7.201-4.082-8.244-8.57-7.567l.371-2.046a.5.5 0 0 0-.8-.482"/></defs><use href="#stashArrowReplyDuotone0" opacity="0.5"/><use href="#stashArrowReplyDuotone0" fill-opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"/><path fill="currentColor" d="m4.833 11.18l-.308-.392zm5.463-4.291l.31.393zm-5.463 5.078l-.308.393zm5.463 4.292l-.309.394zm.801-.482l.492-.09zm-.355-1.955l-.492.09a.5.5 0 0 1 .375-.576zm7.914 3.235l-.453.21zm.64-.073l-.498-.045zm-8.57-7.567l.074.494a.5.5 0 0 1-.567-.583zm.371-2.046l.492.09zm-6.572 3.417l5.462-4.293l.618.787l-5.463 4.292zm0 1.572a1 1 0 0 1 0-1.572l.617.786zm5.462 4.293L4.525 12.36l.617-.786l5.463 4.292zm1.602-.966c.165.906-.878 1.534-1.602.966l.618-.787zm-.355-1.954l.355 1.954l-.984.18l-.355-1.955zm-.609-.397c2.614-.627 4.528-.249 5.908.57c1.367.81 2.148 2.016 2.577 2.941l-.907.42c-.378-.815-1.046-1.829-2.18-2.501c-1.122-.665-2.762-1.034-5.164-.457zm8.485 3.511a.23.23 0 0 0-.114-.116c-.024-.01-.037-.008-.04-.008a.1.1 0 0 0-.058.028a.27.27 0 0 0-.1.188l.996.09c-.044.486-.481.661-.73.688c-.252.027-.676-.049-.861-.45zm-.312.092c.312-3.488-.68-5.332-2.134-6.273c-1.506-.975-3.657-1.087-5.864-.755l-.15-.988c2.282-.344 4.739-.274 6.557.903c1.87 1.211 2.92 3.489 2.587 7.202zm-7.209-9.478l-.372 2.046l-.984-.18l.372-2.045zm-1.602-.966c.724-.568 1.767.06 1.602.966l-.984-.18z"/></svg>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="#" class="avtar avtar-s btn-link-secondary" data-bs-toggle="modal" data-bs-target="#deleteLaporanKerja">
                                            <i class="ph-duotone ph-trash f-22"></i>
                                        </a>
                                    </li>
                                    {{-- <li class="list-inline-item align-bottom me-2">
                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-pencil-simple-line f-22"></i>
                                        </a>
                                    </li>--}}
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('form-pengawas-batubara.pdf', $data['daily']->uuid ) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('form-pengawas-batubara.download', $data['daily']->uuid ) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-printer f-22"></i>
                                        </a>
                                    </li>

                                </ul>
                                @include('form-pengawas-new.delete-preview')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    // range picker
    (function () {
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-5'), {
            buttonClass: 'btn'
        });
    })();

</script>

