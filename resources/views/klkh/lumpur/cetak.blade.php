<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan KKH & KLKH Area Dumping Lumpur</title>
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
            padding: 1.4px;
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
                    <th class="noborder nobg" style="padding: 0px'margin-bttom:0px"><img src="{{ asset('dashboard/assets') }}/images/logo-full.png" width="240px"></th>
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-PRD-66/00/13/01/23</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>
        <h1>Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Dumping di Kolam Air/Lumpur</h1>
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg">PIT</th>
                    <th class="noborder nobg">: {{ $lpr->pit }}</th>
                    <th colspan="" class="noborder nobg">Shift</th>
                    <th colspan="3" class="noborder nobg">: {{ $lpr->shift }}</th>
                </tr>
                <tr>
                    <th class="noborder nobg">Hari/Tanggal</th>
                    <th class="noborder nobg">: {{ Carbon::parse($lpr->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                    <th class="noborder nobg" colspan="">Jam</th>
                    <th class="noborder nobg" colspan="3">: {{ Carbon::parse($lpr->time)->locale('id')->isoFormat('HH:mm') }}</th>
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
                    <th>JALAN</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>Apakah terdapat unit breakdown di jalan</td>
                    <td class="center">{!! $lpr->unit_breakdown_check == 'true' ? '✔️' : '' !!}</td>
                    <td class="center">{!! $lpr->unit_breakdown_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->unit_breakdown_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->unit_breakdown_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Terdapat rambu rambu jalan</td>
                    <td class="center">{!! $lpr->rambu_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->rambu_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->rambu_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->rambu_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Terdapat pelaporan grade jalan Max 12 %</td>
                    <td class="center">{!! $lpr->grade_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->grade_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->grade_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->grade_note !!}</td>
                <tr>
                    <td class="center">4</td>
                    <td>Terdapat Unit Maintenance Jalan (MG, BD, EXC)</td>
                    <td class="center">{!! $lpr->unit_maintenance_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->unit_maintenance_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->unit_maintenance_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->unit_maintenance_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Terdapat unit pengendalian Debu (WT)</td>
                    <td class="center">{!! $lpr->debu_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->debu_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->debu_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->debu_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Lebar jalan min 21 meter</td>
                    <td class="center">{!! $lpr->lebar_jalan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->lebar_jalan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->lebar_jalan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->lebar_jalan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Terdapat area blind spot</td>
                    <td class="center">{!! $lpr->blind_spot_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->blind_spot_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->blind_spot_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->blind_spot_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Kondisi jalan bergelombang (andulating)</td>
                    <td class="center">{!! $lpr->kondisi_jalan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->kondisi_jalan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->kondisi_jalan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->kondisi_jalan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Terdapat Tanggul jalan dengan tinggi 3/4 dari diameter  tyre HD terbesar</td>
                    <td class="center">{!! $lpr->tanggul_jalan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tanggul_jalan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tanggul_jalan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->tanggul_jalan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Terdapat pengelolaan air di jalan saat Hujan (sodetan, drainase)</td>
                    <td class="center">{!! $lpr->pengelolaan_air_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->pengelolaan_air_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->pengelolaan_air_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->pengelolaan_air_note !!}</td>
                </tr>
                <th class="center">B</th>
                <th>DUMPINGAN</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                    <td class="center">1</td>
                    <td>Apakah terdapat crack, patahan penurunan dumpingan</td>
                    <td class="center">{!! $lpr->crack_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->crack_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->crack_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->crack_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Apakah luas area dumpingan mencukupi untuk manuver HD (min 30 meter)</td>
                    <td class="center">{!! $lpr->luas_area_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->luas_area_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->luas_area_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->luas_area_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Apakah terdapat tanggul dumpingan (bundwall) dengan tinggi 3/4 dari diameter tyre HD terbesar</td>
                    <td class="center">{!! $lpr->tanggul_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tanggul_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tanggul_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->tanggul_note !!}</td>
                </tr>
                <tr>
                    <td class="center">4</td>
                    <td>Apakah terdapat free dump di area dumpingan</td>
                    <td class="center">{!! $lpr->free_dump_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->free_dump_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->free_dump_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->free_dump_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Apakah terdapat pengelolaan alokasi  material kurang bagus </td>
                    <td class="center">{!! $lpr->alokasi_material_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->alokasi_material_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->alokasi_material_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->alokasi_material_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Apakah terdapat beda level area dumpingan</td>
                    <td class="center">{!! $lpr->beda_level_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->beda_level_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->beda_level_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->beda_level_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Apakah tinggi dumpingan max 2.5 meter dari permukaan air/lumpur</td>
                    <td class="center">{!! $lpr->tinggi_dumpingan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tinggi_dumpingan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tinggi_dumpingan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->tinggi_dumpingan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Apakah terdapat genangan air di area dumpingan</td>
                    <td class="center">{!! $lpr->genangan_air_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->genangan_air_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->genangan_air_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->genangan_air_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Apakah dumpingan bergelombang</td>
                    <td class="center">{!! $lpr->dumpingan_bergelombang_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->dumpingan_bergelombang_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->dumpingan_bergelombang_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->dumpingan_bergelombang_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Apakah terdapat bendera acuan dumpingan</td>
                    <td class="center">{!! $lpr->bendera_acuan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->bendera_acuan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->bendera_acuan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->bendera_acuan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">11</td>
                    <td>Apakah terdapat rambu jarak dumping 7,5 m</td>
                    <td class="center">{!! $lpr->rambu_jarak_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->rambu_jarak_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->rambu_jarak_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->rambu_jarak_note !!}</td>
                </tr>
                <tr>
                    <td class="center">12</td>
                    <td>Apakah terdapat tower lamp (Penerangan cukup saat gelap/malam hari)</td>
                    <td class="center">{!! $lpr->tower_lamp_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tower_lamp_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->tower_lamp_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->tower_lamp_note !!}</td>
                </tr>
                <tr>
                    <td class="center">13</td>
                    <td>Apakah terdapat penyalur petir (penangkal Petir)</td>
                    <td class="center">{!! $lpr->penyalur_petir_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->penyalur_petir_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->penyalur_petir_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->penyalur_petir_note !!}</td>
                </tr>
                <tr>
                    <td class="center">14</td>
                    <td>Apakah terdapat area tempat berkumpul saat terjadi emergency (Muster Point)</td>
                    <td class="center">{!! $lpr->muster_point_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->muster_point_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->muster_point_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->muster_point_note !!}</td>
                </tr>
                <tr>
                    <td class="center">15</td>
                    <td>Apakah terdapat area parkir sarana dengan safety bund wall</td>
                    <td class="center">{!! $lpr->safety_bundwall_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->safety_bundwall_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->safety_bundwall_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->safety_bundwall_note !!}</td>
                </tr>
                <tr>
                    <td class="center">16</td>
                    <td>Apakah terdapat Ring buoy dengan tali panjang 15 m</td>
                    <td class="center">{!! $lpr->ring_buoy_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->ring_buoy_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->ring_buoy_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->ring_buoy_note !!}</td>
                </tr>
                <tr>
                    <td class="center">17</td>
                    <td>Apakah terdapat sling ware</td>
                    <td class="center">{!! $lpr->sling_ware_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->sling_ware_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->sling_ware_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->sling_ware_note !!}</td>
                </tr>
                <tr>
                    <td class="center">18</td>
                    <td>Apakah terdapat pondok pengawas</td>
                    <td class="center">{!! $lpr->pondok_pengawas_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->pondok_pengawas_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->pondok_pengawas_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->pondok_pengawas_note !!}</td>
                </tr>
                <tr>
                    <td class="center">19</td>
                    <td>Apakah terdapat struktur pengawas</td>
                    <td class="center">{!! $lpr->struktur_pengawas_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->struktur_pengawas_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->struktur_pengawas_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->struktur_pengawas_note !!}</td>
                </tr>
                <tr>
                    <td class="center">20</td>
                    <td>Apakah terdapat Life Jacket untuk Unit Bulldozer</td>
                    <td class="center">{!! $lpr->life_jacket_bulldozer_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->life_jacket_bulldozer_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->life_jacket_bulldozer_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->life_jacket_bulldozer_note !!}</td>
                </tr>
                <tr>
                    <td class="center">21</td>
                    <td>Apakah terdapat nomor Emergenchy di area disposal</td>
                    <td class="center">{!! $lpr->emergency_number_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->emergency_number_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->emergency_number_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->emergency_number_note !!}</td>
                </tr>
                <tr>
                    <td class="center">22</td>
                    <td>Apakah terdapat life jacket untuk Spotter</td>
                    <td class="center">{!! $lpr->life_jacket_spotter_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->life_jacket_spotter_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $lpr->life_jacket_spotter_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $lpr->life_jacket_spotter_note !!}</td>
                </tr>
            </tbody>
        </table>
        Catatan:
        <p class="mb-0">{!! $lpr->additional_notes !!}</p>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <tbody>
                <tr>
                    <td class="noborder nobg">Foremen</td>
                    <td class="noborder nobg">Supervisor</td>
                    <td class="noborder nobg">Superintendent</td>
                </tr>
                <tr>
                    <td class="noborder nobg">@if ($lpr->verified_foreman != null)<img src="{{ $lpr->verified_foreman }}" style="max-width: 70px;">@endif</td>
                    <td class="noborder nobg">@if ($lpr->verified_supervisor != null)<img src="{{ $lpr->verified_supervisor }}" style="max-width: 70px;">@endif</td>
                    <td class="noborder nobg">@if ($lpr->verified_superintendent != null)<img src="{{ $lpr->verified_superintendent }}" style="max-width: 70px;">@endif</td>
                </tr>
                <tr>
                    <td class="noborder nobg">{{ $lpr->nama_foreman ? $lpr->nama_foreman : '.......................' }}</td>
                    <td class="noborder nobg">{{ $lpr->nama_supervisor ? $lpr->nama_supervisor : '.......................' }}</td>
                    <td class="noborder nobg">{{ $lpr->nama_superintendent ? $lpr->nama_superintendent : '.......................' }}</td>
                </tr>
                <tr style="font-size:8pt;">
                    <td class="noborder nobg">
                        {!! $lpr->catatan_verified_foreman != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lpr->catatan_verified_foreman
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $lpr->catatan_verified_supervisor != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lpr->catatan_verified_supervisor
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $lpr->catatan_verified_superintendent != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $lpr->catatan_verified_superintendent
                            : '' !!}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
<script>
    window.print();
</script>
</html>
