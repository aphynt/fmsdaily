<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ public_path('dashboard/assets/images/icon.png') }}" type="image/x-icon">
    <title>Laporan Harian Pengawas Pit Stop</title>
    <style>
        body {
            font-size: 7pt;
            font-family: 'Malgun Gothic', sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Malgun Gothic', sans-serif;
        }

        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            text-align: center;
        }

        td.left {
            text-align: left;
        }

        td.right {
            text-align: right;
        }

        .noborder {
            border: none !important;
        }

        .header {
            background: rgb(207, 207, 207);
        }

        .section-title {
            background: rgb(3, 3, 37);
            color: white;
            font-size: 14pt;
            text-align: center;
        }

        img {
            max-height: 40px;
        }
    </style>
</head>
<body>
    <table>
        <!-- Logo & Form -->
        <tr class="noborder" style="border-bottom:2px solid black;">
            <td colspan="10" class="left noborder">
                <img src="{{ public_path('dashboard/assets/images/logo-full.png') }}" alt="logo" />
            </td>
            <td colspan="11" class="right noborder">FM-PRD-68/00/04/10/23</td>
        </tr>

        <!-- Judul -->
        <tr class="section-title noborder">
            <td colspan="21" class="section-title">Laporan Harian Pengawas Pit Stop</td>
        </tr>

        <!-- Info Header -->
        <tr class="noborder">
            <td colspan="2" class="left noborder">Tanggal</td>
            <td colspan="7" class="left noborder">: {{ date('d-m-Y', strtotime($data['daily']->tanggal)) }}</td>
            <td class="left noborder">Nama Foreman</td>
            <td colspan="11" class="left noborder">: {{ $data['daily']->nama_foreman }}</td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="left noborder">Shift</td>
            <td colspan="7" class="left noborder">: {{ $data['daily']->shift }}</td>
            <td class="left noborder">Nik</td>
            <td colspan="11" class="left noborder">: {{ $data['daily']->nik_foreman }}</td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="left noborder">Lokasi</td>
            <td colspan="7" class="left noborder">: {{ $data['daily']->area }}</td>
            <td class="left noborder">Nama Supervisor</td>
            <td colspan="11" class="left noborder">: {{ $data['daily']->nama_supervisor }}</td>
        </tr>

        <!-- Header Tabel -->
        <tr style="background-color:#D9E1F2;">
            <th rowspan="2">No</th>
            <th rowspan="2">Jenis Unit</th>
            <th rowspan="2">Type Unit</th>
            <th rowspan="2">No. Unit</th>
            <th rowspan="2">Operator<br>(Settingan)</th>
            <th colspan="4">Status</th>
            <th colspan="6" rowspan="2">Operator<br>(Ready)</th>
            <th colspan="6" rowspan="2">Keterangan</th>
        </tr>
        <tr style="background-color:#D9E1F2;">
            <th>Unit<br>Breakdown</th>
            <th>Unit<br>Ready</th>
            <th>Operator<br>Ready</th>
            <th>Durasi</th>
        </tr>

        <!-- Body Tabel -->
        <tbody>
            @foreach ($data['dailyDesc'] as $sp)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sp->jenis_unit }}</td>
                <td>{{ $sp->type_unit }}</td>
                <td>{{ $sp->no_unit }}</td>
                <td class="left" style="{{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                    {{ $sp->opr_settingan }}-{{ $sp->nama_opr_settingan }}
                </td>
                <td>
                    {!! $sp->isOutsideShift ? '<b>'.$sp->time_breakdown.'</b>' : $sp->time_breakdown !!}
                </td>
                <td>{{ $sp->status_unit_ready_fmt }}</td>
                <td>{{ $sp->status_opr_ready_fmt }}</td>
                <td style="{{ $sp->totalMinutes > 30 ? 'color:red;font-weight:bold;' : '' }}">
                    {{ $sp->durasi_eff }}
                </td>
                <td colspan="6" class="left" style="{{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                    {{ $sp->opr_ready }}-{{ $sp->nama_opr_ready }}
                </td>
                <td colspan="6" class="left">
                    {!! $sp->isOutsideShift ? '<b>'.$sp->keterangan.'</b>' : $sp->keterangan !!}
                </td>
            </tr>
            @endforeach

            @if ($data['dailyDesc']->isEmpty())
            <tr>
                <td colspan="21">&nbsp;</td>
            </tr>
            @endif
        </tbody>

        <!-- Catatan -->
        <tr class="noborder">
            <td colspan="21" class="left noborder"><u>Catatan :</u></td>
        </tr>
        <tr class="noborder">
            <td colspan="21" class="left noborder">{!! nl2br(e($data['daily']->catatan_pengawas)) !!}</td>
        </tr>
    </table>
</body>
</html>
