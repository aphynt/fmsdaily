<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan KKH & KLKH Area Loading Point</title>
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
            background-color: #D0CECE;
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
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-PRD-50/01/06/09/24</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>
        <h1>Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Loading Point</h1>
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg">PIT</th>
                    <th class="noborder nobg">: {{ $lp->pit }}</th>
                    <th colspan="" class="noborder nobg">Shift</th>
                    <th colspan="3" class="noborder nobg">: {{ $lp->shift }}</th>
                </tr>
                <tr>
                    <th class="noborder nobg">Hari/Tanggal</th>
                    <th class="noborder nobg">: {{ Carbon::parse($lp->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                    <th class="noborder nobg" colspan="">Jam</th>
                    <th class="noborder nobg" colspan="3">: {{ Carbon::parse($lp->time)->locale('id')->isoFormat('HH:mm') }}</th>
                </tr>
                <tr>
                    <th class="center" rowspan="2">No</th>
                    <th class="center" rowspan="2">Point Yang Diperiksa</th>
                    <th class="center" colspan="3" style="text-align: center;">Cek</th>
                    <th class="center" rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th class="center">Ya</th>
                    <th class="center">Tidak</th>
                    <th class="center">N/A</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>Lokasi loading point tidak dibawah batuan menggantung</td>
                    <td class="center">{!! $lp->loading_point_check == 'true' ? '<img src="' . public_path('check.png') . '">' : '' !!}</td>
                    <td class="center">{!! $lp->loading_point_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->loading_point_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->loading_point_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Permukaan front aman dari bahaya terjatuh atau terperosok</td>
                    <td class="center">{!! $lp->front_surface_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->front_surface_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->front_surface_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->front_surface_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Tinggi dan lebar bench kerja sesuai dengan standar parameter ( Buku Panduan Foreman/Supervisor Lapangan)</td>
                    <td class="center">{!! $lp->bench_work_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->bench_work_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->bench_work_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->bench_work_note !!}</td>
                <tr>
                    <td class="center">4</td>
                    <td>Tinggi tanggul akses jalan masuk loading point 3/4 tinggi roda terbesar</td>
                    <td class="center">{!! $lp->access_dike_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->access_dike_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->access_dike_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->access_dike_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Lebar loading point sesuai dengan standar pada spesifikasi unit loading</td>
                    <td class="center">{!! $lp->loading_point_width_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->loading_point_width_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->loading_point_width_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->loading_point_width_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Terdapat drainage atau paritan kearah sump</td>
                    <td class="center">{!! $lp->drainage_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->drainage_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->drainage_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->drainage_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Loading point tidak bergelombang, tidak berair, dan bebas batuan lepas</td>
                    <td class="center">{!! $lp->no_waves_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->no_waves_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->no_waves_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->no_waves_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Penempatan unit loading sesuai dengan volume material pada area tersebut</td>
                    <td class="center">{!! $lp->unit_placement_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->unit_placement_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->unit_placement_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->unit_placement_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Stok material cukup</td>
                    <td class="center">{!! $lp->material_stock_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->material_stock_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->material_stock_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->material_stock_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Kombinasi unit loading dan unit hauling sesuai</td>
                    <td class="center">{!! $lp->loading_hauling_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->loading_hauling_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->loading_hauling_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->loading_hauling_note !!}</td>
                </tr>
                <tr>
                    <td class="center">11</td>
                    <td>Pengendalian debu sudah dilakukan dengan baik ( penyiraman terjadwal dan jumlahnya mencukupi )</td>
                    <td class="center">{!! $lp->dust_control_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->dust_control_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->dust_control_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->dust_control_note !!}</td>
                </tr>
                <tr>
                    <td class="center">12</td>
                    <td>Penerangan areal kerja mencukupi dan terarah untuk pekerjaan malam hari</td>
                    <td class="center">{!! $lp->lighting_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->lighting_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->lighting_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->lighting_note !!}</td>
                </tr>
                <tr>
                    <td class="center">13</td>
                    <td>Kebersihan sekitar area pembuangan & Housekeeping baik (bebas sampah)</td>
                    <td class="center">{!! $lp->housekeeping_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->housekeeping_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $lp->housekeeping_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $lp->housekeeping_note !!}</td>
                </tr>
            </tbody>
        </table>
        Catatan:
        <p class="mb-0">{!! $lp->additional_notes !!}</p>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <tbody>
                <tr>
                    <td class="noborder nobg">Foremen</td>
                    <td class="noborder nobg">Supervisor</td>
                    <td class="noborder nobg">Superintendent</td>
                </tr>
                <tr>
                    <td class="noborder nobg">@if ($lp->verified_foreman != null)<img src="data:image/png;base64, {!! $lp->verified_foreman !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($lp->verified_supervisor != null)<img src="data:image/png;base64, {!! $lp->verified_supervisor !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($lp->verified_superintendent != null)<img src="data:image/png;base64, {!! $lp->verified_superintendent !!} " style="max-width: 100px;">@endif</td>
                </tr>
                <tr>
                    <td class="noborder nobg">{{ $lp->nama_foreman ? $lp->nama_foreman : '.......................' }}</td>
                    <td class="noborder nobg">{{ $lp->nama_supervisor ? $lp->nama_supervisor : '.......................' }}</td>
                    <td class="noborder nobg">{{ $lp->nama_superintendent ? $lp->nama_superintendent : '.......................' }}</td>
                </tr>
                <tr style="font-size:8pt;">
                    <td class="noborder nobg">
                        {!! $lp->catatan_verified_foreman != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lp->catatan_verified_foreman
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $lp->catatan_verified_supervisor != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lp->catatan_verified_supervisor
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $lp->catatan_verified_superintendent != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lp->catatan_verified_superintendent
                            : '' !!}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
</html>
