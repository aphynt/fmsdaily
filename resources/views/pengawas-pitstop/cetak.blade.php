<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <title>Laporan Harian Pengawas Pit Stop</title>
    <style>
        body {
            font-size: 9pt;
            font-family: 'Malgun Gothic', sans-serif;
            margin: 0;
            padding: 0;
        }
        @media print {
            @page {
                size: A4 landscape;
            }

            table,
            th,
            td {
                border-collapse: collapse;
                page-break-inside: auto;
            }
            tr > th{
                text-align: center;
            }

            th.noborder,
            td.noborder {
                border: none;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
                border: 1px solid black;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            th,
            td {
                padding: 5px;
                text-align: left;
            }

            table {
                width: 100%;
            }

            /* Judul section */
            tr.section-title {
                page-break-before: always;
                page-break-inside: avoid;
                background: rgb(10, 10, 51);
                color: white;
                text-align: left;
            }

            tr.section-title,
            tr.section-title+tr,
            /* baris kosong sesudah judul */
            tr.section-title.prev-row {
                /* baris kosong sebelum judul, beri class prev-row di HTML nanti */
                page-break-before: always;
                page-break-inside: avoid;
            }
        }


        table,
        th,
        td {
            /* border: 1px solid black; */
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th.noborder,
        td.noborder {
            border: none;
        }

        tr {
            page-break-inside: avoid;
            /* Jangan pisahkan satu baris ke halaman berbeda */
            page-break-after: auto;
        }
         tr > th{
                text-align: center;
            }

        th,
        tr,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 3px;
            text-align: left;
        }

        table {
            width: 100%;
            /* table-layout: fixed; */
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
            /* Supaya footer tabel muncul di setiap halaman */
        }

        td {
            height: 15px;
            vertical-align: middle;
            text-align: center;

        }

        .center {
            text-align: center;
            border: 0px;
        }

        .header {
            background: rgb(207, 207, 207);
        }

        .noborder {
            border-left: none;
            border-right: none;
        }
        .left{
            text-align: left;
        }
        .right{
            text-align: right;
        }
    </style>
</head>
<body>
    <table>
        <tr class="left noborder" style="border-top:none;border-bottom:2px solid black;">
            <td colspan="10" class="left noborder" style="border-top:none;">
                <img alt="Company Logo" height="40"
                src="{{ asset('dashboard/assets/images/logo-full.png') }}"
                alt="logo disini"  />
            </td>
            <td colspan="11" class="right noborder" >FM-PRD-68/00/04/10/23</td>
        </tr>
        <tr class="noborder">
            <td colspan="21" class="noborder" style="background:rgb(3, 3, 37);color:white;font-size:14pt">Laporan Harian Pengawas Pit Stop</td>
        </tr>
        <tr class="noborder" style="border-top:none;border-bottom:none;">
            <td class="left noborder" colspan="2" style="border-top:none;border-bottom:none;">Tanggal</td>
            <td class="left noborder" colspan="7" style="border-top:none;border-bottom:none;">: {{ date('d-m-Y', strtotime($data['daily']->tanggal)) }}</td>
            <td class="left noborder" style="border-top:none;border-bottom:none;">Nama Foreman</td>
            <td class="left noborder" colspan="11" style="border-top:none;border-bottom:none;">: {{ $data['daily']->nama_foreman }}</td>
        </tr>
         <tr class="noborder" style="border-top:none;border-bottom:none;">
            <td class="left noborder" colspan="2">Shift</td>
            <td class="left noborder" colspan="7">: {{ $data['daily']->shift }}</td>
            <td class="left noborder">Nik</td>
            <td class="left noborder" colspan="12">: {{ $data['daily']->nik_foreman }}</td>
        </tr>
         <tr class="noborder" style="border-top:none;border-bottom:none;">
            <td class="left noborder" colspan="2">Lokasi</td>
            <td class="left noborder" colspan="7">: {{ $data['daily']->area }}</td>
            <td class="left noborder">Nama Supervisor</td>
            <td class="left noborder" colspan="11">: {{ $data['daily']->nama_supervisor }}</td>
        </tr>
        <tr  style="background-color:#D9E1F2;">
            <th rowspan="2">No</th>
            <th rowspan="2">Jenis Unit</th>
            <th rowspan="2">Type Unit</th>
            <th rowspan="2">No. Unit</th>
            <th rowspan="2"><strong>Operator <br>(Settingan)</strong></th>
            <th colspan="4">Status</th>
            <th rowspan="2" colspan="6"><strong>Operator <br>(Ready)</strong></th>
            <th rowspan="2" colspan="6">Keterangan</th>
        </tr>
        <tr  style="background-color:#D9E1F2;">
            <th>Unit <br>Breakdown</th>
            <th>Unit<br>Ready</th>
            <th>Operator<br>Ready</th>
            <th>Durasi</th>
        </tr>

        <tbody>
            @foreach ($data['dailyDesc'] as $sp)
            <tr>
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td style="padding-left:2px;">{{ $sp->jenis_unit }}</td>
                <td style="padding-left:2px;">{{ $sp->type_unit }}</td>
                <td style="padding-left:2px;">{{ $sp->no_unit }}</td>
                <td style="padding-left:2px;text-align: left; {{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                    {{ $sp->opr_settingan }}-{{ $sp->nama_opr_settingan }}
                </td>
                <td style="padding-left:2px;">
                    {!! $sp->isOutsideShift ? '<b>'.$sp->time_breakdown.'</b>' : $sp->time_breakdown !!}
                </td>
                <td style="padding-left:2px;">{{ $sp->status_unit_ready_fmt }}</td>
                <td style="padding-left:2px;">{{ $sp->status_opr_ready_fmt }}</td>
                <td style="padding-left:2px; {{ $sp->totalMinutes > 30 ? 'color:red;font-weight:bold;' : '' }}">
                    {{ $sp->durasi_eff }}
                </td>
                <td colspan="6" style="padding-left:2px;text-align: left; {{ $sp->isDifferentOpr ? 'color:blue;' : '' }}">
                    {{ $sp->opr_ready }}-{{ $sp->nama_opr_ready }}
                </td>
                <td colspan="6" style="padding-left:2px;text-align: left;">
                    {!! $sp->isOutsideShift ? '<b>'.$sp->keterangan.'</b>' : $sp->keterangan !!}
                </td>
            </tr>
            @endforeach
            @if ($data['dailyDesc']->isEmpty())
                <tr>
                    <td style="text-align: center"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td style="padding-left:2px;"></td>
                    <td colspan="6" style="padding-left:2px;"></td>
                    <td colspan="6" style="padding-left:2px;"></td>
                </tr>

            @endif
        </tbody>
        <tr class="noborder" style="border-top:none;border-bottom:none;">
            <td class="left noborder" colspan="21" style="border-top:none;border-bottom:none;"><u>Catatan :</u></td>
        </tr>
        <tr class="noborder" style="border-top:none;border-bottom:none;">
            <td class="left noborder" colspan="21" style="border-top:none;border-bottom:none;">{!! nl2br(e($data['daily']->catatan_pengawas)) !!}</td>
        </tr>
    </table>
</body>
<script>
    window.print();
</script>
</html>
