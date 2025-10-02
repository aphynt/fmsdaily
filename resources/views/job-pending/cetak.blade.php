<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Pending Pengawas</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/fontawesome.css">
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <style>
        @media print {
            @page {
                size: A4 landscape;
            }

            body {
                font-size: 10pt;
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
            padding: 5px;
            text-align: left;
        }

        table {
            width: 100%;
            table-layout: fixed;
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
        <tr style="border-bottom:none;">
            <td rowspan="4" colspan="2" style="border-top:none;border-bottom:none;">
                <img src="{{ asset('dashboard/assets') }}/images/logo-full.png" width="240px">
            </td>
            <td rowspan="4" colspan="2"><h2>JOB PENDING PENGAWAS</h2></td>
            <td class="left noborder">Hari / Tanggal</td>
            <td class="left noborder" colspan="2">: {{ \Carbon\Carbon::parse($data[0]->tanggal_pending)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
        </tr>
        <tr style="border-top:none;border-bottom:none;">
            <td class="left noborder" style="border-top:none;border-bottom:none;">Shift</td>
            <td class="left noborder" colspan="2" style="border-top:none;border-bottom:none;">: {{ $data[0]->shift }}</td>
        </tr>
        <tr style="border-top:none;border-bottom:none;">
            <td class="left noborder" style="border-top:none;border-bottom:none;">Section</td>
            <td class="left noborder" colspan="2" style="border-top:none;border-bottom:none;">: {{ $data[0]->section }}</td>
        </tr>
        <tr style="border-top:none;">
            <td class="left noborder" style="border-top:none;border-bottom:none;">Lokasi</td>
            <td class="left noborder" colspan="2" style="border-top:none;border-bottom:none;">: {{ $data[0]->lokasi }}</td>
        </tr>

        <tr style="border-bottom: 3px double black;">
            <th>No.</th>
            <th colspan="3">Aktivitas / Pekerjaan</th>
            <th>Unit Support</th>
            <th colspan="2">Elevasi</th>
        </tr>
        @foreach ($data as $item)
        <tr style="border-top:none;border-bottom:none;">
            <td class="noborder">{{ $loop->iteration }}</td>
            <td colspan="3" class="left" style="border-top:none;border-bottom:none;">{{ $item->aktivitas }}</td>
            <td class="left" style="border-top:none;border-bottom:none;">{{ $item->unit }}</td>
            <td colspan="2" class="left" style="border-top:none;border-bottom:none;">{{ $item->elevasi }}</td>
        </tr>
        @endforeach
        <tr style="border-bottom:none;">
            <th colspan="7" style="border-bottom:none; text-align:left; padding-left:40px;">Issue/Catatan:</th>
        </tr>
        <tr style="border-bottom:none;border-top:none;">
            <td colspan="7" style="border-bottom:none;border-top:none; text-align:left; padding-left:40px;">{!! nl2br(e($data[0]->issue)) !!}</td>
        </tr>
        @if ($data[0]->catatan_verified_diterima != null)
        <tr style="border-bottom:none;">
            <th colspan="7" style="border-bottom:none; text-align:left; padding-left:40px;">Catatan Penerima:</th>
        </tr>
        <tr style="border-bottom:none;border-top:none;">
            <td colspan="7" style="border-bottom:none;border-top:none; text-align:left; padding-left:40px;">{!! nl2br(e($data[0]->catatan_verified_diterima)) !!}</td>
        </tr>
        @endif
        @if ($data[0]->foto != null || $data[0]->foto2 != null)
            <tr style="border-bottom:none;">
                <th colspan="7" style="border-bottom:none; text-align:left; padding-left:40px;">
                    Gambar:
                </th>
            </tr>
            <tr style="border-bottom:none; border-top:none;">
                <td colspan="7" style="border-bottom:none; border-top:none; text-align:left; padding-left:40px;">
                    <div style="display:flex; gap:20px; align-items:center;">
                        @if ($data[0]->foto != null)
                            <div>
                                <img src="{{ $data[0]->foto }}"
                                    style="max-width:150px; object-fit:contain; border-radius:6px;">
                            </div>
                        @endif

                        @if ($data[0]->foto2 != null)
                            <div>
                                <img src="{{ $data[0]->foto2 }}"
                                    style="max-width:150px; object-fit:contain; border-radius:6px;">
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @endif

        <tr style="border-bottom:none;">
            <th colspan="4" style="border-bottom:none; text-align:left; padding-left:40px;">Dibuat Oleh</th>
            <th colspan="3" style="border-bottom:none; text-align:left; padding-left:40px;">Diterima Oleh</th>
        </tr>

        <tr style="border-top:none;border-bottom:none;">
            <td colspan="4" style="border-top:none;border-bottom:none; text-align:left; padding-left:40px;">
                {{ $data[0]->jabatan_dibuat }}
            </td>
            <td colspan="3" style="border-top:none;border-bottom:none; text-align:left; padding-left:40px;">
                {{ $data[0]->jabatan_diterima }}
            </td>
        </tr>
        <tr style="border-top:none;border-bottom:none;">
            <td colspan="4" style="border-top:none;border-bottom:none; text-align:left; padding-left:40px;">
                @if ($data[0]->verified_dibuat != null)
                    <img src="{{ $data[0]->verified_dibuat_qr }}" style="max-width: 70px;">
                @endif
            </td>
            <td colspan="3" style="border-top:none;border-bottom:none; text-align:left; padding-left:40px;">
                @if ($data[0]->verified_diterima != null)
                    <img src="{{ $data[0]->verified_diterima_qr }}" style="max-width: 70px;">
                @endif
            </td>
        </tr>
        <tr style="border-top:none;">
            <td colspan="4" style="border-top:none; text-align:left; padding-left:40px;">
                {{ $data[0]->nama_dibuat }}
            </td>
            <td colspan="3" style="border-top:none; text-align:left; padding-left:40px;">
                {{ $data[0]->nama_diterima }}
            </td>
        </tr>

    </table>
</body>
<script>
    window.print();
</script>
</html>
