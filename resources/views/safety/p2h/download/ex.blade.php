<!DOCTYPE html>
<html lang="en">
    @php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2H Excavator</title>
    <style>
        @page {
            size: A4;
            margin: 5mm;
            orientation: landscape;
        }
        @media print {
            body {
                margin: 0.2in;
                padding: 0;
                font-size: xx-small;
            }
            table {
                page-break-inside: avoid;
            }

        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 6.5pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 0.8px;
            text-align: center;
        }
        th {
            background-color: #D9D9D9;
        }
        th[rowspan="3"] {
            vertical-align: middle;
        }
        th[colspan="2"] {
            background-color: #e0e0e0;
        }
        tr td:nth-child(2){
            text-align: left;
        }
        .left{
            text-align:left
        }
        .right{
            text-align: right;
        }
        .no_border{
            border-left: none;
            border-right: none;
        }
        .header{
            display:flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #000;
        }

        .noborder {
            border-top-color: white;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }

        .nobg{
            background-color: white;
        }
        .kanan {
            text-align: right;
        }

        .kiri {
            text-align: left;
        }
    </style>
</head>
<body>
    <table>
            <thead>
                <tr>
                    <th class="noborder nobg kiri" style="padding: 0px'margin-top:0px"><img src="{{ public_path('dashboard/assets/images/logo-full.png') }}" width="240px"></th>
                    <th colspan="10" class="noborder nobg kanan"><p style="margin: 0;">FM-SHE-51/04/08/05/25</p></th>
                </tr>
            </thead>
        </table>
    <h1 style="text-align: center;">
        PEMERIKSAAN DAN PERAWATAN HARIAN (P2H)
    </h1>
    <table>
        <tr style="border-top:1px solid #000; border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">Unit</td>
            <td style="border:none" colspan="6">: EXCAVATOR</td>
            <td rowspan="2"><h3>EXC</h3></td>
        </tr>
        <tr style="border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">No. Unit</td>
            <td style="border:none;text-align:left;" colspan="6">: {{ $data->first()->VHC_ID }}</td>
        </tr>
        <tr style="border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">Tanggal</td>
            <td style="border:none;text-align:left;" colspan="6">: {{ Carbon::parse($data->first()->DATEVERIFIED_OPERATOR)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
            <td rowspan="4"><img src="{{ public_path('dashboard/assets') }}/images/K3.png" width="40px"></td>
        </tr>
        <tr style="border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">Shift</td>
            <td style="border:none;text-align:left;" colspan="6">: {{ $data->first()->SHIFTDESC }}</td>
        </tr>
        <tr style="border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">Jam</td>
            <td style="border:none;text-align:left;" colspan="6">: {{ Carbon::parse($data->first()->DATEVERIFIED_OPERATOR)->locale('id')->isoFormat('HH:mm') }}</td>
        </tr>
        <tr style="border-right:1px solid #000">
            <td style="border:none;text-align:left;" colspan="2">Hm/Km</td>
            <td style="border:none;text-align:left;" colspan="6">:</td>
        </tr>
    <!-- </table>
    <table>
        <thead> -->
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" colspan="">PIC</th>
                <th rowspan="2">BAGIAN YANG HARUS DIPERIKSA</th>
                <th rowspan="2">KODE BAHAYA</th>
                <th rowspan="" colspan="2">KONDISI</th>
                <th>CATATAN / TEMUAN</th>
                <th colspan="2">COMMENT / JAWABAN</th>
            </tr>
            <tr>
                <th>BAIK/NORMAL</th>
                <th>RUSAK/TDK NORMAL</th>
                <th>Oprt./ Driver/Mekanik</th>
                <th colspan="2">Foreman/Spv atas temuan pada kolom 6</th>
            </tr>
            <tr>
                <th colspan="">0</th>
                <th colspan="">1</th>
                <th colspan="">2</th>
                <th colspan="">3</th>
                <th colspan="">4</th>
                <th colspan="">5</th>
                <th colspan="">6</th>
                <th colspan="2">7</th>
            </tr>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: center">O</td>
                    <td style="text-align: left">{{ $item->ITEMDESCRIPTION }}</td>
                    <td>{{ $item->GROUPID }}</td>
                    <td>
                        @if ($item->VALUE == 1)
                            <img src="{{ public_path('check.png') }}">
                        @elseif($item->VALUE == 2)
                            -
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($item->VALUE == 0)
                            <img src="{{ public_path('check.png') }}">
                        @elseif($item->VALUE == 2)
                            -
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->NOTES }}</td>
                    <td style="text-align: center;min-width:10px;">{{ $item->KBJ }}</td>
                    <td style="text-align: left">{{ $item->JAWABAN }}</td>
                </tr>
            @endforeach
        <!-- </tbody> -->
    </table>

    <p>
        <strong>PENTING:</strong>
    </p>
    <div class="container">
        <p>
            <ol style="display:flex;">
                <div class="satu">
                    <li>Kode ''AA" = Unit tidak bisa dioperasikan sebelum ada</li>
                    <li>Kode " A" = Kerusakan yang harus diperbaiki dalam waktu 1 x 1 SHIFT</li>
                    <li>P2H harus dilaksanakan diawal shift dan ditandatangani oleh Driver/Oprt sebelum dioperasikan kemudian diserahkan kepada Foreman/SPV</li>
                    <li>Mengoperasikan alat dengan kerusakan kode bahaya AA, akan dikenai sanksi sesuai  peraturan.</li>
                    <li>O =  Operator</li>
                    <li>KBJ = Kode Bahaya setelah penilaian resiko</li>
                </div>
            </ol>
        </p>

    </div>
    <table>
        <thead>
            <tr>
                <th colspan="2"></th>
                <th>Oprt. / Driver</th>
                <th colspan="3">Mekanik *)</th>
                <th>Foreman / Spv</th>
                <th colspan="2">S/Intendent**)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">Nama</td>
                <td style="padding-left:10px">{{ $data->first()->NAMAOPERATOR }}</td>
                <td style="padding-left:10px" colspan="3">{{ $data->first()->NAMAMEKANIK }}</td>
                <td style="padding-left:10px;text-align:left;">
                    {{ $data->first()->NAMAFOREMAN
                        ? $data->first()->NAMAFOREMAN
                        : ($data->first()->NAMASUPERVISOR ?? '')
                    }}
                </td>
                <td style="padding-left:10px;text-align:left;" colspan="2">{{ $data->first()->NAMASUPERINTENDENT }}</td>
            </tr>
            <tr>
                <td colspan="2">NIP</td>
                <td style="padding-left:10px">{{ $data->first()->NRPOPERATOR }}</td>
                <td style="padding-left:10px" colspan="3">{{ $data->first()->NRPMEKANIK }}</td>
                <td style="padding-left:10px;text-align:left;">
                    {{ $data->first()->NRPFOREMAN
                        ? $data->first()->NRPFOREMAN
                        : ($data->first()->NRPSUPERVISOR ?? '')
                    }}
                </td>
                <td style="padding-left:10px;text-align:left;" colspan="2">{{ $data->first()->NRPSUPERINTENDENT }}</td>
            </tr>
            <tr>
                <td colspan="2">Tanggal</td>
                <td style="padding-left:10px">{{ $data->first()->DATEVERIFIED_OPERATOR != null ? Carbon::parse($data->first()->DATEVERIFIED_OPERATOR)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : '' }}</td>

                <td style="padding-left:10px;text-align:left;" colspan="3">{{ $data->first()->DATEVERIFIED_MEKANIK != null ? Carbon::parse($data->first()->DATEVERIFIED_MEKANIK)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : '' }}</td>

                <td style="padding-left:10px;text-align:left;">
                    {{
                        $data->first()->DATEVERIFIED_FOREMAN
                            ? Carbon::parse($data->first()->DATEVERIFIED_FOREMAN)->locale('id')->isoFormat('D MMMM YYYY HH:mm')
                            : ($data->first()->DATEVERIFIED_SUPERVISOR
                                ? Carbon::parse($data->first()->DATEVERIFIED_SUPERVISOR)->locale('id')->isoFormat('D MMMM YYYY HH:mm')
                                : ''
                            )
                    }}
                </td>

                <td style="padding-left:10px" colspan="2">{{ $data->first()->DATEVERIFIED_SUPERINTENDENT != null ? Carbon::parse($data->first()->DATEVERIFIED_SUPERINTENDENT)->locale('id')->isoFormat('D MMMM YYYY HH:mm') : '' }}</td>
            </tr>
            <tr>
                <td colspan="2">T. Tangan</td>
                <td style="padding-left:10px">@if ($data->first()->VERIFIED_OPERATOR != null)<img src="data:image/png;base64, {!! $data->first()->VERIFIED_OPERATOR !!} " style="max-width: 100px;">@endif</td>
                <td style="padding-left:10px" colspan="3">@if ($data->first()->VERIFIED_MEKANIK != null)<img src="data:image/png;base64, {!! $data->first()->VERIFIED_MEKANIK !!} " style="max-width: 100px;">@endif</td>
                <td style="padding-left:10px;text-align:left;">
                    @if ($data->first()->VERIFIED_FOREMAN != null)<img src="data:image/png;base64, {!! $data->first()->VERIFIED_FOREMAN !!} " style="max-width: 100px;">
                    @elseif ($data->first()->VERIFIED_SUPERVISOR != null)<img src="data:image/png;base64, {!! $data->first()->VERIFIED_SUPERVISOR !!} " style="max-width: 100px;">
                    @endif
                </td>
                <td style="padding-left:10px;text-align:left;" colspan="2">@if ($data->first()->VERIFIED_SUPERINTENDENT != null)<img src="data:image/png;base64, {!! $data->first()->VERIFIED_SUPERINTENDENT !!} " style="max-width: 100px;">@endif</td>
            </tr>
        </tbody>
    </table>
    <p>
        *) Bisa tidak di tanda tangani apabila tidak ada temuan<br/>
        **) Memeriksa (Bulanan)  P2H  yang di isi untuk memastikan P2H dilaksanakan dengan baik<br/>
        ***) Kerusakan akibat insiden harus dilaporkan dalam waktu 1 x 24 jam
    </p>
</body>
</html>
