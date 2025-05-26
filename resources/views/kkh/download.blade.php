<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKH - {{ $kkh->first()->NAMA_PENGISI }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        @media print {
            body {
                margin: 0.2in;
                padding: 0;

            }

            table {
                page-break-inside: avoid;
            }

        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* margin-top: 20px; */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 0.8px;
            /*text-align: center;*/
        }

        th {
            background-color: #f2f2f2;
        }

        th[rowspan="3"] {
            vertical-align: middle;
        }

        th[colspan="2"] {
            background-color: #e0e0e0;
        }

        tr td:nth-child(2) {
            text-align: left;
        }

        .left {
            text-align: left
        }

        .right {
            text-align: right;
        }

        .no_border {
            /* border-left: none; */
            border-right: none;
        }
        .nobg{
            background-color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #000;
        }

    </style>
</head>

<body>
        <table style="margin-top: 20px">
            <thead>
                <tr class="no_border">
                    <th class="nobg no_border left"><img src="{{ public_path('dashboard/assets') }}/images/logo-full.png" width="240px"></th>
                    <th colspan="5" class="nobg right" style="border-left: none;"><p style="margin: 0;">FM-SHE-118/05/08/05/25</p></th>
                </tr>
            </thead>
        </table>

        <table>

            <tr style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">Perusahaan</td>
                <td style="border:none;text-align=left" colspan="">: {{ $kkh->first()->PERUSAHAAN }}</td>
                <td style="border:none; padding-left:70px;" rowspan="6" colspan="11"><h1>
        KESIAPAN KERJA HARIAN (KKH)
        </h1></td>
            </tr>
            <tr style="border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">Nama</td>
                <td style="border:none;text-align=left" colspan="">: {{ $kkh->first()->NAMA_PENGISI }}</td>
            </tr>
            <tr style="border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">NIK</td>
                <td rowspan="" colspan="2" style="border:none;">: {{ $kkh->first()->NIK_PENGISI }}</td>
            </tr>
            <tr style="border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">Departemen</td>
                <td style="border:none;text-align=left" colspan="">: {{ $kkh->first()->DEPARTEMEN }}</td>
            </tr>
            <tr style="border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">Jabatan</td>
                <td style="border:none;text-align=left" colspan="">: {{ $kkh->first()->JABATAN }}</td>
            </tr>
            <tr style="border-left:1px solid #000; border-right:1px solid #000">
                <td style="border:none;text-align=left" colspan="2">Siklus Kerja</td>
                <td style="border:none;text-align=left" colspan="6">:  {{ $kkh->first()->SIKLUS_KERJA }}</td>
            </tr>
            <!-- </table>
    <table>
        <thead> -->
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" colspan="">Hari / Tanggal</th>
                <th rowspan="2">Jam Pulang Kerja <br>(Sampai di rumah / mess)</th>
                <th colspan="3">Jam Tidur</th>
                <th rowspan="2">Jam <br>Berangkat</th>
                <th rowspan="2">Fit <br />Bekerja</th>
                <th rowspan="2">Keluhan Fisik<br>/Mental</th>
                <th rowspan="2">Masalah Pribadi <br>(Sampaikan jika ada)</th>
                <th colspan="4">TTD</th>
            </tr>
            <tr>
                <th style="text-align:center">Mulai</th>
                <th style="text-align:center">Bangun</th>
                <th style="text-align:center">Total</th>
                <th colspan="2" style="text-align:center">Istri/Wakil/Rekan</th>
                <th colspan="2" style="text-align:center">Nama Pengawas</th>
            </tr>

            @foreach ($kkh as $item)
                <tr>
                    <td rowspan="2" style="text-align: center;">{{ $loop->iteration }}</td>
                    <td rowspan="2" style="text-align: left;padding-left:5px;">{{ \Carbon\Carbon::parse($item->TANGGAL_DIBUAT)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->JAM_PULANG }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->JAM_TIDUR }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->JAM_BANGUN }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->TOTAL_TIDUR }} Jam</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->JAM_BERANGKAT }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->FIT_BEKERJA }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->KELUHAN }}</td>
                    <td rowspan="2" style="text-align: center;">{{ $item->MASALAH_PRIBADI }}</td>
                    <td>Nama</td>
                    <td>{{ \Illuminate\Support\Str::title($item->NAMA_VERIFIKASI) }} <br> ({{ $item->VERIFIKASI }})</td>
                    <td>Nama</td>
                    <td>@if ($item->NIK_PENGAWAS != "" || $item->NIK_PENGAWAS != null) {{ $item->NAMA_PENGAWAS }} @endif</td>
                </tr>
                <tr>
                    <td colspan="2">
                        @if ($item->QR_CODE_VERIFIKASI != null)
                            <img src="{{ $item->QR_CODE_VERIFIKASI }}" style="max-width: 52px;" alt="QR Verifikasi" />
                        @else
                            -
                        @endif
                    </td>
                    <td colspan="2">
                        @if ($item->QR_CODE_PENGAWAS != null)
                            <img src="{{ $item->QR_CODE_PENGAWAS }}" style="max-width: 52px;" alt="QR Pengawas" />
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

</body>
</html>
