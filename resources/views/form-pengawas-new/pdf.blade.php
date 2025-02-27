<html>

<head>
    <title>
        Laporan Harian Foreman Produksi
    </title>
    <style>

            .container {
                width: 100%;
                margin: 0;
                border: none;
                padding: 0;
            }

        body {
            margin: 0;
            padding: 0;
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
    display: flex;
    justify-content: space-between;
    margin: 20px;
}

.grid-container > div {
    width: 48%; /* Untuk memberikan jarak antara kolom */
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

    <table class="info-table">
        <tr>
            <td colspan="14"><img alt="Company Logo" height="40"
                src="{{ public_path('dashboard/assets/images/logo-full.png') }}"
                alt="logo disini"  /></td>
            <td colspan="14"  style="text-align: right;"><b>FM-PRD-03/03/06/02/24</b></td>
        </tr>
    </table>
    <hr>
    <h2 style="text-align: center;"><u>LAPORAN HARIAN FOREMAN PRODUKSI</u></h2>
    <table class="info-table">
        <tr>
            <td colspan="5">Tanggal</td>
            <td colspan="9">: {{ date('d-m-Y', strtotime($data['daily']->tanggal)) }}</td>
            <td colspan="5">Nama Foreman</td>
            <td colspan="9">: {{ $data['daily']->nama_foreman }}</td>
        </tr>
        <tr>
            <td colspan="5">Shift</td>
            <td colspan="9">: {{ $data['daily']->shift }}</td>
            <td colspan="5">NIK Foreman</td>
            <td colspan="9">: {{ $data['daily']->nik_foreman }}</td>
        </tr>
        <tr>
            <td colspan="5">Unit Kerja</td>
            <td colspan="9">: {{ $data['daily']->lokasi }}</td>
            <td colspan="5">Nama Supervisor</td>
            <td colspan="9">: {{ $data['daily']->nama_supervisor }}</td>
        </tr>
        <tr>
            <td colspan="5">Jam Kerja</td>
            <td colspan="9">: {{ $data['daily']->shift == 'Siang' ? '06:30 - 18:30' : '18:30 - 06:30' }}</td>
            <td colspan="5"></td>
            <td colspan="9"></td>

        </tr>
    </table>
    <h4>
        A. FRONT LOADING
    </h4>
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">Brand</th>
                <th rowspan="2">Type</th>
                <th rowspan="2">No Unit</th>
                <th>Shift</th>
                <th colspan="12">Jam</th>
            </tr>
            @if ($data['daily']->shift == 'Siang')
            <tr>
                <th>Siang</th>
                @foreach(['07-08', '08-09', '09-10', '10-11',
                '11-12', '12-13', '13-14', '14-15',
                '15-16', '16-17', '17-18', '18-19'] as $slot)
                <th>{{ $slot }}</th>
                @endforeach
            </tr>
            @else
            <tr>
                <th>Malam</th>
                @foreach(['19-20', '20-21', '21-22', '22-23',
                '23-24', '24-01', '01-02', '02-03',
                '03-04', '04-05', '05-06', '06-07'] as $slot)
                <th>{{ $slot }}</th>
                @endforeach
            </tr>
            @endif
        </thead>
        <tbody>
            @foreach($data['front'] as $brand => $units)
            @php
            $groupedByType = $units->groupBy('type');
            @endphp
            @foreach($groupedByType as $type => $typeUnits)
            @foreach($typeUnits as $index => $unit)
            <tr>
                <!-- Tampilkan Brand hanya di baris pertama dari grup -->
                @if($loop->parent->first && $index === 0)
                <td rowspan="{{ $units->count() }}">{{ $brand }}</td>
                @endif
                <!-- Tampilkan Type hanya di baris pertama dari grup Type -->
                @if($index === 0)
                <td rowspan="{{ $typeUnits->count() }}">{{ $type }}</td>
                @endif
                <td colspan="2">{{ $unit['nomor_unit'] }}</td>
                @foreach($unit['siang'] as $slot)
                <td>{!! $slot->status !!}</td>
                @endforeach
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Keterangan: beri tanda centang (√) pada unit excavator yang diawasi
    </div>
    <h4>
        B.  ALAT SUPPORT
    </h4>
    <table class="data_table">
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
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td style="padding-left:2px;">{{ $sp->nomor_unit }}</td>
                <td style="padding-left:2px;">{{ $sp->nama_operator }}</td>
                <td style="padding-left:2px;">{{ date('d-m-Y', strtotime($sp->tanggal)) }}</td>
                <td style="text-align: center">{{ $sp->hm_awal }}</td>
                <td style="text-align: center">{{ $sp->hm_akhir }}</td>
                <td style="text-align: center">{{ number_format($sp->hm_akhir - $sp->hm_awal, 2) }}</td>
                <td style="text-align: center">{{ $sp->hm_cash }}</td>
                <td style="padding-left:2px;">{{ $sp->keterangan }}</td>
            </tr>
            @endforeach
            @if ($data['support']->isEmpty())
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>
    <div style="font-size: 8pt;"><i>KET:</i></div>
    <div class="grid-container" style="width: 100%; margin: 20px 0;">
        <!-- Grid pertama: Tabel pertama -->
        <div class="grid-table" style="float: left; width: 48%; margin-right: 2%;">
            <table>
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

                    @foreach ($data['front'] as $brand => $units)
                        @foreach ($units as $unit)
                            @if ($data['daily']->shift == 'Siang')
                                @foreach ($unit['siang'] as $index => $slot)
                                    @if($slot->keterangan != "")
                                        <tr>
                                            <td style="border: none; border-bottom: 1px solid black; text-align:left; padding-top:7px;">
                                                <!-- Menampilkan nomor unit -->
                                                {{ $unit['nomor_unit'] }} =>
                                                <!-- Waktu -->
                                                ({{ $timeSlots['siang'][$index] }})
                                                <!-- Keterangan -->
                                                {{ $slot->keterangan }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($unit['malam'] as $index => $slot)
                                    @if($slot->keterangan != "")
                                        <tr>
                                            <td style="border: none; border-bottom: 1px solid black; text-align:left; padding-top:7px;">
                                                <!-- Menampilkan nomor unit -->
                                                {{ $unit['nomor_unit'] }} =>
                                                <!-- Waktu -->
                                                ({{ $timeSlots['malam'][$index] }})
                                                <!-- Keterangan -->
                                                {{ $slot->keterangan }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach

                    @if ($data['front']->isEmpty())
                        @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td style="border: none; border-bottom: 1px solid black; text-align:left; padding-top:17px;">
                                    &nbsp;
                                </td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Grid kedua: Tabel kedua -->
        <div class="grid-table" style="float: left; width: 48%;">
            <table>
                <thead>
                    <tr>
                        <th>Dibuat</th>
                        <th>Diperiksa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if ($data['daily']->verified_foreman != null)
                                <img src="data:image/png;base64, {!! $data['daily']->verified_foreman !!}" style="max-width: 100px;">
                            @endif
                            <br>
                            @if ($data['daily']->verified_foreman != null)
                                {{ $data['daily']->nama_foreman }}
                            @endif
                        </td>
                        <td>
                            @if ($data['daily']->verified_supervisor != null)
                                <img src="data:image/png;base64, {!! $data['daily']->verified_supervisor !!}" style="max-width: 100px;">
                            @endif
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

</body>


</html>
