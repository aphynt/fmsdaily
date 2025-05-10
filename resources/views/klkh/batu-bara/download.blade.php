<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan KKH & KLKH Area Batu Bara</title>
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
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-PRD-72/00/12/03/24</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>
        <h1>Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Batubara</h1>
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg">PIT</th>
                    <th class="noborder nobg">: {{ $bb->pit }}</th>
                    <th colspan="" class="noborder nobg">Shift</th>
                    <th colspan="3" class="noborder nobg">: {{ $bb->shift }}</th>
                </tr>
                <tr>
                    <th class="noborder nobg">Hari/Tanggal</th>
                    <th class="noborder nobg">: {{ Carbon::parse($bb->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                    <th class="noborder nobg" colspan="">Jam</th>
                    <th class="noborder nobg" colspan="3">: {{ Carbon::parse($bb->time)->locale('id')->isoFormat('HH:mm') }}</th>
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
                <tr>
                    <th class="center">A</th>
                    <th>Coal Loading Point</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>Lokasi loading point tidak dibawah batuan menggantung</td>
                    <td class="center">{!! $bb->loading_point_check == 'true' ? '<img src="' . public_path('check.png') . '">' : '' !!}</td>
                    <td class="center">{!! $bb->loading_point_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->loading_point_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->loading_point_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Permukaan front aman dari bahaya terjatuh atau terperosok</td>
                    <td class="center">{!! $bb->permukaan_front_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->permukaan_front_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->permukaan_front_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->permukaan_front_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Tinggi dan lebar bench kerja sesuai dengan standar</td>
                    <td class="center">{!! $bb->tinggi_bench_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->tinggi_bench_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->tinggi_bench_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->tinggi_bench_note !!}</td>
                <tr>
                    <td class="center">4</td>
                    <td>Lebar loading point sesuai dengan standar pada spesifikasi unit loading</td>
                    <td class="center">{!! $bb->lebar_loading_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_loading_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_loading_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->lebar_loading_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Terdapat drainase atau paritan ke arah sump</td>
                    <td class="center">{!! $bb->drainase_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->drainase_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->drainase_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->drainase_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Penempatan unit loading sesuai dengan volume Batubara</td>
                    <td class="center">{!! $bb->penempatan_unit_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->penempatan_unit_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->penempatan_unit_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->penempatan_unit_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Terdapat pelabelan seam batubara di unit (hauler dan loader)</td>
                    <td class="center">{!! $bb->pelabelan_seam_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->pelabelan_seam_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->pelabelan_seam_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->pelabelan_seam_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Unit yang bekerja memiliki lampu dengan intensitas cahaya yang tinggi
                    </td>
                    <td class="center">{!! $bb->lampu_unit_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lampu_unit_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lampu_unit_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->lampu_unit_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Unit yang bekerja bersih dan sudah dicuci</td>
                    <td class="center">{!! $bb->unit_bersih_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->unit_bersih_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->unit_bersih_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->unit_bersih_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Penerangan area kerja mencukupi dan terarah untuk pekerjaan malam hari (20-50 lux)</td>
                    <td class="center">{!! $bb->penerangan_area_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->penerangan_area_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->penerangan_area_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->penerangan_area_note !!}</td>
                </tr>
                <tr>
                    <td class="center">11</td>
                    <td>Housekeeping baik (bebas sampah)</td>
                    <td class="center">{!! $bb->housekeeping_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->housekeeping_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->housekeeping_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->housekeeping_note !!}</td>
                </tr>
                <tr>
                    <td class="center">12</td>
                    <td>Telah dilakukan pengukuran roof Batubara oleh survey</td>
                    <td class="center">{!! $bb->pengukuran_roof_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->pengukuran_roof_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->pengukuran_roof_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->pengukuran_roof_note !!}</td>
                </tr>
                <tr>
                    <td class="center">13</td>
                    <td>Telah dilakukan cleaning pada Batubara dan Batubara bebas kontaminan</td>
                    <td class="center">{!! $bb->cleaning_batubara_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->cleaning_batubara_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->cleaning_batubara_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->cleaning_batubara_note !!}</td>
                </tr>
                <tr>
                    <td class="center">14</td>
                    <td>Tidak terdapat genangan air pada Batubara</td>
                    <td class="center">{!! $bb->genangan_air_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->genangan_air_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->genangan_air_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->genangan_air_note !!}</td>
                </tr>
                <tr>
                    <td class="center">15</td>
                    <td>Tidak terdapat big coal</td>
                    <td class="center">{!! $bb->big_coal_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->big_coal_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->big_coal_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->big_coal_note !!}</td>
                </tr>
                <tr>
                    <td class="center">16</td>
                    <td>Stock material cukup</td>
                    <td class="center">{!! $bb->stock_material_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->stock_material_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->stock_material_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->stock_material_note !!}</td>
                </tr>
                <th class="center">B</th>
                <th>Jalan Tambang</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                    <td class="center">1</td>
                    <td>Lebar jalan angkut 3.5 x lebar unit terbesar</td>
                    <td class="center">{!! $bb->lebar_jalan_angkut_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_jalan_angkut_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_jalan_angkut_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->lebar_jalan_angkut_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Lebar jalan tikungan 4 x lebar unit terbesar</td>
                    <td class="center">{!! $bb->lebar_jalan_tikungan_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_jalan_tikungan_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->lebar_jalan_tikungan_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->lebar_jalan_tikungan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Super elevasi sesuai standar</td>
                    <td class="center">{!! $bb->super_elevasi_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->super_elevasi_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->super_elevasi_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->super_elevasi_note !!}</td>
                </tr>
                <tr>
                    <td class="center">4</td>
                    <td>Tersedia safety berm pada areal yang mempunyai beda tinggi lebih dari 1 meter</td>
                    <td class="center">{!! $bb->safety_berm_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->safety_berm_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->safety_berm_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->safety_berm_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Tinggi tanggul minimal 2/3 tinggi ban unit terbesar</td>
                    <td class="center">{!! $bb->tinggi_tanggul_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->tinggi_tanggul_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->tinggi_tanggul_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->tinggi_tanggul_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Terdapat safety post pada tanggul jalan</td>
                    <td class="center">{!! $bb->safety_post_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->safety_post_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->safety_post_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->safety_post_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Tersedia drainase dan tidak ada genangan air di jalan angkut</td>
                    <td class="center">{!! $bb->drainase_genangan_air_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->drainase_genangan_air_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->drainase_genangan_air_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->drainase_genangan_air_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Terdapat median jalan pada tikungan yang sudutnya lebih besar dari 60°</td>
                    <td class="center">{!! $bb->median_jalan_check == 'true' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->median_jalan_check == 'false' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td class="center">{!! $bb->median_jalan_check == 'n/a' ? '<img src="' . public_path('check.png') . '">' : "" !!}</td>
                    <td>{!! $bb->median_jalan_note !!}</td>
                </tr>
            </tbody>
        </table>
        Catatan:
        <p class="mb-0">{!! $bb->additional_notes !!}</p>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <tbody>
                <tr>
                    <td class="noborder nobg">Foremen</td>
                    <td class="noborder nobg">Supervisor</td>
                    <td class="noborder nobg">Superintendent</td>
                </tr>
                <tr>
                    <td class="noborder nobg">@if ($bb->verified_foreman != null)<img src="data:image/png;base64, {!! $bb->verified_foreman !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($bb->verified_supervisor != null)<img src="data:image/png;base64, {!! $bb->verified_supervisor !!} " style="max-width: 100px;">@endif</td>
                    <td class="noborder nobg">@if ($bb->verified_superintendent != null)<img src="data:image/png;base64, {!! $bb->verified_superintendent !!} " style="max-width: 100px;">@endif</td>
                </tr>
                <tr>
                    <td class="noborder nobg">{{ $bb->nama_foreman ? $bb->nama_foreman : '.......................' }}</td>
                    <td class="noborder nobg">{{ $bb->nama_supervisor ? $bb->nama_supervisor : '.......................' }}</td>
                    <td class="noborder nobg">{{ $bb->nama_superintendent ? $bb->nama_superintendent : '.......................' }}</td>
                </tr>
                <tr style="font-size:8pt;">
                    <td class="noborder nobg">
                        {!! $bb->catatan_verified_foreman != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $bb->catatan_verified_foreman
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $bb->catatan_verified_supervisor != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $bb->catatan_verified_supervisor
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $bb->catatan_verified_superintendent != null
                            ? '<img src="' . public_path("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $bb->catatan_verified_superintendent
                            : '' !!}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
</html>
