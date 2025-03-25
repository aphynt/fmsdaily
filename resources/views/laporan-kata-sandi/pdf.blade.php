<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kata Sandi</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/fontawesome.css">
    <style>
        @page {
            size: A4;
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            /* line-height: 1.6; */
            font-size: 12px;
        }

        .container {
            width: 100%;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 15px;
        }

        h5 {
            margin: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            /*text-align: center;*/
        }

        th {
            /* background-color: #D0CECE; */
            text-align: center;
        }



        h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .point-table {
            margin-top: 5px;
        }

        tr th {
            text-align: left;
        }

        .kanan {
            text-align: right;
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

        .center {
            text-align: center;
        }

        .box {
            display: flex;
            justify-content: space-between;
        }

        .box-vcenter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            align-content: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg" style="padding: 0px'margin-bttom:0px"><img src="{{ public_path('dashboard/assets/images/logo-full.png') }}" width="240px"></th>
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-SHE-146/01/04/08/21</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>

        <table style="border: 1px solid black; border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th colspan="2">Kata - Sandi: {{ $data['kataSandi']->kata_sandi }}</th>
                    <th>Tgl/Bln/Thn</th>
                    <th>{{ Carbon::parse($data['kataSandi']->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                </tr>
                <tr>
                    <th rowspan="2">Disampaikan Oleh Pengawas</th>

                    <th>Nama: {{ $data['kataSandi']->nik_pic }} | {{ $data['kataSandi']->pic }}</th>
                    <th rowspan="2">Bagian</th>
                    <th rowspan="2">Produksi <br><br> Shift: {{ $data['kataSandi']->shift }}</th>
                </tr>
                <tr>
                    <th>
                        Tanda Tangan:
                        <img style="padding-top: 20px;padding-bottom:0px;" src="data:image/png;base64,{{ base64_encode(QrCode::size(60)->generate('Telah diverifikasi oleh: ' . $data['kataSandi']->pic)) }}">
                    </th>
                </tr>
            </thead>
        </table>
        <h4 style="text-align: center; margin: 0; padding: 0;">PENGAWASAN PADA JAM KRITIKAL - MENGANTUK</h4>
        <h4 style="text-align: center; margin: 0; padding: 0;">1. PENGAWAS MENGHUBUNGI OPERATOR TIAP JAM MEMAKAI RADIO</h4>
        <h4 style="text-align: center; margin: 0; padding: 0;">2. OPERATOR MENJAWAB DENGAN KATA SANDI</h4>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">No. Unit</th>
                    <th style="text-align: center">Shift</th>
                    <th style="text-align: center">Jam Monitor</th>
                    <th style="text-align: center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['kataSandiUnit'] as $ksu)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ksu->no_unit }}</td>
                        <td>{{ $data['kataSandi']->shift }}</td>
                        <td>{{ Carbon::parse($ksu->jam_monitor)->locale('id')->isoFormat('HH:mm') }}</td>
                        <td>{{ $ksu->keterangan }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <br><br>
        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center">PIMPINAN DEPARTEMEN</th>
                    <th style="text-align: center">NAMA</th>
                    <th style="text-align: center">TANDA TANGAN</th>
                </tr>
                <tr>
                    <th style="padding: 40px"></th>
                    <th></th>
                </tr>
            </thead>
        </table>

    </div>
</body>
</html>
