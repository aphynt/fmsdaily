<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan KKH & KLKH Area OGS</title>
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
                    <th colspan="5" class="noborder nobg kanan"><p style="margin: 0;">FM-PRD-71/00/08/03/24</p></th>
                </tr>
            </thead>
        </table>
        <div class="box-vcenter" style="display: flex; align-items: center; justify-content: space-between;">


        </div>
        <hr>
        <h1>Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area OGS</h1>
        <table class="point-table">
            <thead>
                <tr>
                    <th class="noborder nobg">PIT</th>
                    <th class="noborder nobg">: {{ $ogs->pit }}</th>
                    <th colspan="" class="noborder nobg">Shift</th>
                    <th colspan="3" class="noborder nobg">: {{ $ogs->shift }}</th>
                </tr>
                <tr>
                    <th class="noborder nobg">Hari/Tanggal</th>
                    <th class="noborder nobg">: {{ Carbon::parse($ogs->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</th>
                    <th class="noborder nobg" colspan="">Jam</th>
                    <th class="noborder nobg" colspan="3">: {{ Carbon::parse($ogs->time)->locale('id')->isoFormat('HH:mm') }}</th>
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
                    <th>Tempat Parkir</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>Rata dan padat</td>
                    <td class="center">{!! $ogs->rata_padat_check == 'true' ? '✔️' : '' !!}</td>
                    <td class="center">{!! $ogs->rata_padat_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rata_padat_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rata_padat_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Parkir kendaraan sarana LV/Support/Daily Check terpisah</td>
                    <td class="center">{!! $ogs->parkir_terpisah_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->parkir_terpisah_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->parkir_terpisah_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->parkir_terpisah_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Tidak ada ceceran oli</td>
                    <td class="center">{!! $ogs->ceceran_oli_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->ceceran_oli_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->ceceran_oli_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->ceceran_oli_note !!}</td>
                <tr>
                    <td class="center">4</td>
                    <td>Tidak ada genangan air</td>
                    <td class="center">{!! $ogs->genangan_air_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->genangan_air_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->genangan_air_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->genangan_air_note !!}</td>
                </tr>
                <th class="center">B</th>
                <th>Rambu</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                    <td class="center">1</td>
                    <td>Terdapat rambu informasi berkumpul darurat</td>
                    <td class="center">{!! $ogs->rambu_darurat_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_darurat_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_darurat_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rambu_darurat_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Terdapat rambu-rambu lalulintas sesuai standar (Larangan, petunjuk, batas kecepatan)</td>
                    <td class="center">{!! $ogs->rambu_lalulintas_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_lalulintas_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_lalulintas_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rambu_lalulintas_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Terdapat rambu tanda batas berhenti atau antri masing-masing unit</td>
                    <td class="center">{!! $ogs->rambu_berhenti_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_berhenti_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_berhenti_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rambu_berhenti_note !!}</td>
                </tr>
                <tr>
                    <td class="center">4</td>
                    <td>Terdapat rambu petunjuk/tanda masuk dan keluar</td>
                    <td class="center">{!! $ogs->rambu_masuk_keluar_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_masuk_keluar_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_masuk_keluar_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rambu_masuk_keluar_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Terdapat rambu kapasitas OGS</td>
                    <td class="center">{!! $ogs->rambu_ogs_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_ogs_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->rambu_ogs_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->rambu_ogs_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Terdapat papan nama dibagian tanggul luar menghadap akses jalan yang berisi nama OGS, penanggung jawab area dan No kontak</td>
                    <td class="center">{!! $ogs->papan_nama_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->papan_nama_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->papan_nama_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->papan_nama_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Terdapat informasi emergency call</td>
                    <td class="center">{!! $ogs->emergency_call_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->emergency_call_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->emergency_call_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->emergency_call_note !!}</td>
                </tr>
                <th class="center">C</th>
                <th>Lokasi Kerja</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tr>
                <tr>
                    <td class="center">1</td>
                    <td>Tersedia tempat sampah</td>
                    <td class="center">{!! $ogs->tempat_sampah_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tempat_sampah_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tempat_sampah_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->tempat_sampah_note !!}</td>
                </tr>
                <tr>
                    <td class="center">2</td>
                    <td>Terdapat penyalur petir dengan nilai tahanan grounding max 5 Ohm dan mencakup seluruh area</td>
                    <td class="center">{!! $ogs->penyalur_petir_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->penyalur_petir_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->penyalur_petir_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->penyalur_petir_note !!}</td>
                </tr>
                <tr>
                    <td class="center">3</td>
                    <td>Tersedia tempat istirahat yang memadai</td>
                    <td class="center">{!! $ogs->tempat_istirahat_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tempat_istirahat_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tempat_istirahat_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->tempat_istirahat_note !!}</td>
                </tr>
                <tr>
                    <td class="center">4</td>
                    <td>Tersedia APAR</td>
                    <td class="center">{!! $ogs->apar_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->apar_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->apar_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->apar_note !!}</td>
                </tr>
                <tr>
                    <td class="center">5</td>
                    <td>Tersedia  kotak P3K</td>
                    <td class="center">{!! $ogs->kotak_p3k_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->kotak_p3k_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->kotak_p3k_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->kotak_p3k_note !!}</td>
                </tr>
                <tr>
                    <td class="center">6</td>
                    <td>Penerangan 20 Lux</td>
                    <td class="center">{!! $ogs->penerangan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->penerangan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->penerangan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->penerangan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">7</td>
                    <td>Terdapat kamar mandi dengan fasilitas air bersih</td>
                    <td class="center">{!! $ogs->kamar_mandi_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->kamar_mandi_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->kamar_mandi_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->kamar_mandi_note !!}</td>
                </tr>
                <tr>
                    <td class="center">8</td>
                    <td>Permukaan tanah rata atau maksimal kemiringan 2%</td>
                    <td class="center">{!! $ogs->permukaan_tanah_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->permukaan_tanah_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->permukaan_tanah_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->permukaan_tanah_note !!}</td>
                </tr>
                <tr>
                    <td class="center">9</td>
                    <td>Terdapat akses jalan keluar dan masuk dengan dilengkapi rambu</td>
                    <td class="center">{!! $ogs->akses_jalan_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->akses_jalan_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->akses_jalan_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->akses_jalan_note !!}</td>
                </tr>
                <tr>
                    <td class="center">10</td>
                    <td>Tinggi tanggul 1/3 diameter roda terbesar dan lebar tanggul 2 meter</td>
                    <td class="center">{!! $ogs->tinggi_tanggul_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tinggi_tanggul_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->tinggi_tanggul_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->tinggi_tanggul_note !!}</td>
                </tr>
                <tr>
                    <td class="center">11</td>
                    <td>Lebar jalur Bus 5 meter</td>
                    <td class="center">{!! $ogs->lebar_bus_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->lebar_bus_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->lebar_bus_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->lebar_bus_note !!}</td>
                </tr>
                <tr>
                    <td class="center">12</td>
                    <td>Lebar jalur HD 24 meter (jalur HD dan emergency)</td>
                    <td class="center">{!! $ogs->lebar_hd_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->lebar_hd_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->lebar_hd_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->lebar_hd_note !!}</td>
                </tr>
                <tr>
                    <td class="center">13</td>
                    <td>Terdapat Jalur emergency HD kosongan dan muatan</td>
                    <td class="center">{!! $ogs->jalur_hd_check == 'true' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->jalur_hd_check == 'false' ? '✔️' : "" !!}</td>
                    <td class="center">{!! $ogs->jalur_hd_check == 'n/a' ? '✔️' : "" !!}</td>
                    <td>{!! $ogs->jalur_hd_note !!}</td>
                </tr>
            </tbody>
        </table>
        Catatan:
        <p class="mb-0">{!! $ogs->additional_notes !!}</p>

        <table style="width: 100%; text-align: center; border-spacing: 10px;">
            <tbody>
                <tr>
                    <td class="noborder nobg">Foremen</td>
                    <td class="noborder nobg">Supervisor</td>
                    <td class="noborder nobg">Superintendent</td>
                </tr>
                <tr>
                    <td class="noborder nobg">@if ($ogs->verified_foreman != null)<img src="{{ $ogs->verified_foreman }}" style="max-width: 70px;">@endif</td>
                    <td class="noborder nobg">@if ($ogs->verified_supervisor != null)<img src="{{ $ogs->verified_supervisor }}" style="max-width: 70px;">@endif</td>
                    <td class="noborder nobg">@if ($ogs->verified_superintendent != null)<img src="{{ $ogs->verified_superintendent }}" style="max-width: 70px;">@endif</td>
                </tr>
                <tr>
                    <td class="noborder nobg">{{ $ogs->nama_foreman ? $ogs->nama_foreman : '.......................' }}</td>
                    <td class="noborder nobg">{{ $ogs->nama_supervisor ? $ogs->nama_supervisor : '.......................' }}</td>
                    <td class="noborder nobg">{{ $ogs->nama_superintendent ? $ogs->nama_superintendent : '.......................' }}</td>
                </tr>
                <tr style="font-size:8pt;">
                    <td class="noborder nobg">
                        {!! $ogs->catatan_verified_foreman != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $ogs->catatan_verified_foreman
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $ogs->catatan_verified_supervisor != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $ogs->catatan_verified_supervisor
                            : '' !!}
                    </td>
                    <td class="noborder nobg">
                        {!! $ogs->catatan_verified_superintendent != null
                            ? '<img src="' . asset("dashboard/assets/images/widget/writing.png") . '" alt="">: ' . $ogs->catatan_verified_superintendent
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
