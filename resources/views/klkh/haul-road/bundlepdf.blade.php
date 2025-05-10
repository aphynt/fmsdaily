<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan KKH & KLKH Area Haul Road</title>
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

@foreach ($hr as $haulroad)
<body>
    <div class="container">
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg" style="padding: 0px'margin-bttom:0px"><img src="{{ public_path('dashboard/assets/images/logo-full.png') }}" width="240px"></th>
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-PRD-51/01/18/10/22</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>
        <h1>Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Haul Road</h1>
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg">PIT</th>
                    <th class="noborder nobg">: {{ $haulroad->pit }}</th>
                    <th colspan="" class="noborder nobg">Shift</th>
                    <th colspan="3" class="noborder nobg">: {{ $haulroad->shift }}</th>
                </tr>
                <tr>
                    <th class="noborder nobg">Hari/Tanggal</th>
                    <th class="noborder nobg">: {{ Carbon::parse($haulroad->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                    <th class="noborder nobg" colspan="">Jam</th>
                    <th class="noborder nobg" colspan="3">: {{ Carbon::parse($haulroad->time)->locale('id')->isoFormat('HH:mm') }}</th>
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
                    <td>Lebar jalan angkut 3,5x unit terbesar</td>
                    <td class="center">{!! $haulroad->road_width_check == 'true' ? '<img src="' . public_path('check.png') . '">' : '' !!}</td>
                    <td class="center">{!! $haulroad->road_width_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->road_width_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->road_width_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Lebar jalan tikungan 4x unit terbesar</td>
                    <td class="center">{!! $haulroad->curve_width_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->curve_width_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->curve_width_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->curve_width_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Super elevasi sesuai dengan standar</td>
                    <td class="center">{!! $haulroad->super_elevation_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->super_elevation_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->super_elevation_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->super_elevation_note !!}</td>
                <tr>
                    <td class="center">4</td>
                    <td>Tersedia safety berm pada areal yang mempunyai beda tinggi lebih dari 1 meter</td>
                    <td class="center">{!! $haulroad->safety_berm_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->safety_berm_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->safety_berm_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->safety_berm_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Tanggul jalan minimal 3/4 tinggi ban unit terbesar</td>
                    <td class="center">{!! $haulroad->tanggul_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->tanggul_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->tanggul_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->tanggul_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Terdapat patok safety pada jarak 20 meter dengan tinggi 2 meter</td>
                    <td class="center">{!! $haulroad->safety_patok_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->safety_patok_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->safety_patok_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->safety_patok_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Tersedia drainage dan tidak ada genangan air di jalan angkut</td>
                    <td class="center">{!! $haulroad->drainage_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->drainage_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->drainage_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->drainage_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Terdapat median jalan pada tikungan yang sudutnya lebih besar dari 60o</td>
                    <td class="center">{!! $haulroad->median_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->median_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->median_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->median_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Intersection sesuai dengan standar</td>
                    <td class="center">{!! $haulroad->intersection_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->intersection_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->intersection_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->intersection_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Tersedia rambu-rambu lalu lintas jalan dan post guide lengkap (ada lapisan pantul cahaya)</td>
                    <td class="center">{!! $haulroad->traffic_sign_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->traffic_sign_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->traffic_sign_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->traffic_sign_note !!}</td>
                </tr>
                <tr>
                    <td class="center">11</td>
                    <td>Tersedia rambu-rambu dan lampu (  untuk pekerjaan malam hari ) di persimpangan jalan dan tidak ada blind spot</td>
                    <td class="center">{!! $haulroad->night_work_sign_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->night_work_sign_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->night_work_sign_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->night_work_sign_note !!}</td>
                </tr>
                <tr>
                    <td class="center">12</td>
                    <td>Kondisi jalan cross fall dan tidak bergelombang</td>
                    <td class="center">{!! $haulroad->road_condition_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->road_condition_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->road_condition_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->road_condition_note !!}</td>
                </tr>
                <tr>
                    <td class="center">13</td>
                    <td>Adanya jalur pemisah atau marka jalan, bila diperlukan</td>
                    <td class="center">{!! $haulroad->divider_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->divider_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->divider_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->divider_note !!}</td>
                </tr>
                <tr>
                    <td class="center">14</td>
                    <td>Jalur angkut rata, tidak bergelombang, dan bebas dari tumpahan material dan spoil-spoil</td>
                    <td class="center">{!! $haulroad->haul_route_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->haul_route_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->haul_route_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->haul_route_note !!}</td>
                </tr>
                <tr>
                    <td class="center">15</td>
                    <td>Terdapat penyiraman jalan sebagai pengendalian debu pada jalan</td>
                    <td class="center">{!! $haulroad->dust_control_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->dust_control_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->dust_control_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->dust_control_note !!}</td>
                </tr>
                <tr>
                    <td class="center">16</td>
                    <td>Tersedia petugas pengatur simpang- 4</td>
                    <td class="center">{!! $haulroad->intersection_officer_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->intersection_officer_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->intersection_officer_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->intersection_officer_note !!}</td>
                </tr>
                <tr>
                    <td class="center">17</td>
                    <td>Lampu merah berfungsi baik</td>
                    <td class="center">{!! $haulroad->red_light_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->red_light_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $haulroad->red_light_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $haulroad->red_light_note !!}</td>
                </tr>
            </tbody>
        </table>
        Catatan:
        <p class="mb-0">{!! $haulroad->additional_notes !!}</p>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <tbody>
                <tr>
                    <td class="noborder nobg">Foremen</td>
                    <td class="noborder nobg">Supervisor</td>
                    <td class="noborder nobg">Superintendent</td>
                </tr>
                <tr>
                    <td class="noborder nobg">@if ($haulroad->verified_foreman != null)<img src="data:image/png;base64, {!! $haulroad->verified_foreman !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($haulroad->verified_supervisor != null)<img src="data:image/png;base64, {!! $haulroad->verified_supervisor !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($haulroad->verified_superintendent != null)<img src="data:image/png;base64, {!! $haulroad->verified_superintendent !!} " style="max-width: 100px;">@endif</td>
                </tr>
                <tr>
                    <td class="noborder nobg">{{ $haulroad->nama_foreman ? $haulroad->nama_foreman : '.......................' }}</td>
                    <td class="noborder nobg">{{ $haulroad->nama_supervisor ? $haulroad->nama_supervisor : '.......................' }}</td>
                    <td class="noborder nobg">{{ $haulroad->nama_superintendent ? $haulroad->nama_superintendent : '.......................' }}</td>
                </tr>
                <tr style="font-size:8pt;">
                    <td class="noborder nobg">
                        {!! $haulroad->catatan_verified_foreman != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $haulroad->catatan_verified_foreman
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $haulroad->catatan_verified_supervisor != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $haulroad->catatan_verified_supervisor
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $haulroad->catatan_verified_superintendent != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $haulroad->catatan_verified_superintendent
                            : '' !!}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
@endforeach
</html>
