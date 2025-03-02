@include('layout.head', ['title' => 'Laporan SAP'])
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
@php
    $imageCountTemuan = 0;
    $imageCountTemuan = count($data['imageTemuan']); // Hitung jumlah gambar
    // Tentukan kelas grid berdasarkan jumlah gambar
    if ($imageCountTemuan == 3) {
        $gridClass = 'col-md-4';
    } elseif ($imageCountTemuan == 4) {
        $gridClass = 'col-md-3';
    } else {
        $gridClass = 'col-md-4';
    }
@endphp
@php
    $imageCountTindakLanjut = count($data['imageTindakLanjut']); // Hitung jumlah gambar
    // Tentukan kelas grid berdasarkan jumlah gambar
    if ($imageCountTindakLanjut == 3) {
        $gridClass = 'col-md-4';
    } elseif ($imageCountTindakLanjut == 4) {
        $gridClass = 'col-md-3';
    } else {
        $gridClass = 'col-md-4';
    }
@endphp
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
                                        <a href="{{ route('form-pengawas-sap.show') }}"><span class="badge bg-primary">Kembali</span></a>
                                    </div>
                                </div>
                            </div>
                            <h2 style="text-align: center;"><u>LAPORAN SAP PENGAWAS</u></h2>
                            <div class="col-md-3 mb-3">
                                <label>Tanggal Pelaporan</label>
                                <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data['report']->created_at)) }}" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Jam Kejadian</label>
                                <input type="text" class="form-control" value="{{ date('H:m', strtotime($data['report']->jam_kejadian)) }}" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Shift</label>
                                <input type="text" class="form-control" value="{{ $data['report']->shift ? $data['report']->shift : "-" }}" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Area</label>
                                <input type="text" class="form-control" value="{{ $data['report']->area ? $data['report']->area : "-" }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Temuan KTA/TTA</label>
                                <textarea type="text"  class="form-control" rows="5" readonly>{{ $data['report']->temuan ? $data['report']->temuan : "-" }}</textarea>
                            </div>
                            <h4>Foto Temuan</h4>
                            <div class="row">
                                @if (count($data['imageTemuan']) > 0)
                                    @foreach ($data['imageTemuan'] as $imageTemuan)
                                        <div class="{{ $gridClass }} mb-3">
                                            <img src="{{ asset('storage') }}/{{ $imageTemuan->path }}" alt="Photo Temuan" class="img-thumbnail custom-img">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p>-</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label>Risiko</label>
                                <textarea type="text"  class="form-control" rows="5" readonly>{{ $data['report']->risiko ? $data['report']->risiko : "-" }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Pengendalian</label>
                                <textarea type="text"  class="form-control" rows="5" readonly>{{ $data['report']->pengendalian ? $data['report']->pengendalian : "-" }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Tindak Lanjut</label>
                                <textarea type="text"  class="form-control" rows="5" readonly>{{ $data['report']->tindak_lanjut ? $data['report']->tindak_lanjut : "-" }}</textarea>
                            </div>
                            <h4>Foto Tindak Lanjut</h4>
                            <div class="row">
                                @if (count($data['imageTindakLanjut']) > 0)
                                    @foreach ($data['imageTindakLanjut'] as $imageTindakLanjut)
                                        <div class="{{ $gridClass }} mb-3">
                                            <img src="{{ asset('storage') }}/{{ $imageTindakLanjut->path }}" alt="Photo Kejadian" class="img-thumbnail custom-img">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <p>-</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')


