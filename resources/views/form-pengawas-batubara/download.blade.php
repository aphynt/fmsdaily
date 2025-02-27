<html>

<head>
    <title>
        LAPORAN HARIAN PENGAWAS BATUBARA
    </title>
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                margin: 0;
                border: none;
                padding: 0;
            }
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }
        /* table{
            width: 100%;
            table-layout: fixed;
        }
        table, tr, td, th{
            border-collapse: collapse;
        }
        tr, td, th{
            width:20pt;
        } */
        table{
            page-break-inside:auto
        }
		table {
            -fs-table-paginate: paginate;
        }
        tr{
            page-break-inside:avoid;
            page-break-after:auto;
        }
        table{
            /* border:1px solid #000; */
            border-collapse:collapse;
            table-layout:fixed;
        }
        tr td{
            /* border:1px solid #000; */
            border-collapse:collapse;

			/* padding:.1rem; */
        }
        table tr td, table tr th{
            font-size: x-small;
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

        .data-table th {
            background-color: #f2f2f2;
        }

        .data-table th[colspan] {
            text-align: center;
        }

        .data-table td[colspan] {
            text-align: left;
        }
        .data-table tr:nth-child(odd) {
            background-color: #f2f2f2; /* Warna zebra (warna abu-abu muda untuk baris ganjil) */
        }

        .data-table tr:nth-child(even) {
            background-color: #ffffff; /* Warna putih untuk baris genap */
        }

        .footer {
            font-size: 10px;
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
            width: 100%;
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
</head>

<body>
    <div class="header">
        <div class="flex">
            <img alt="Company Logo" height="40"
                src="{{ asset('dashboard/assets/images/logo-full.png') }}"
                alt="logo disini"  />

        </div>
        <div class="doc-number">
            <p>
                <b>FM-PRD-54/00/26/08/24</b>
            </p>
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
                        <td style="padding-top: 5px;padding-bottom: 5px;">{!! $data['daily']->verified_supervisor !!}
                            <br>
                            @if ($data['daily']->verified_supervisor != null)
                                {{ $data['daily']->nama_supervisor }}
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
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</body>


</html>
