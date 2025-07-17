@include('layout.head', ['title' => 'Roster Kerja'])
@include('layout.sidebar')
@include('layout.header')
@php
    // Menggunakan Carbon untuk mendapatkan jumlah hari dalam bulan saat ini
    $jumlahHari = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
@endphp
<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Roster Kerja</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <form action="" method="get">
                            <div class="mb-3 row d-flex align-items-center">
                                <div class="col-3">
                                    <label>Tahun</label>
                                    <select class="form-select" name="tahun" required>
                                        @php
                                            $currentYear = date('Y');
                                            $years = range($currentYear - 1, $currentYear + 3);
                                        @endphp
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label>Bulan</label>
                                    <select class="form-select" name="bulan">
                                        @php
                                            $bulan = [
                                                1 => 'Januari',
                                                2 => 'Februari',
                                                3 => 'Maret',
                                                4 => 'April',
                                                5 => 'Mei',
                                                6 => 'Juni',
                                                7 => 'Juli',
                                                8 => 'Agustus',
                                                9 => 'September',
                                                10 => 'Oktober',
                                                11 => 'November',
                                                12 => 'Desember',
                                            ];
                                            $currentMonth = now()->month;
                                        @endphp
                                        @foreach($bulan as $key => $month)
                                            <option value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1">
                                    <button type="submit" class="badge bg-primary" style="font-size:16px;border:none">Tampilkan</button>
                                </div>

                                @if (Auth::user()->role == 'ADMIN')
                                <div class="col-3">
                                    <div class="d-flex gap-2">
                                        <a href="#" onclick="downloadExport()" class="badge bg-info" style="font-size:16px; border:none;">Export</a>
                                        <button type="button" class="badge bg-secondary" style="font-size:16px; border:none;" data-bs-toggle="modal" data-bs-target="#importRoster">Import</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </form>

                        @include('roster-kerja.modal.import')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table class="table table-striped table-hover table-bordered nowrap" style="font-size:10pt">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Jabatan</th>
                                        <th rowspan="2">Unit Kerja</th>
                                        <th colspan="2">Pengawas</th>
                                        <th colspan="{{ $jumlahHari }}">Tanggal</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        @for ($i = 1; $i <= $jumlahHari ; $i++)
                                            <th>{{ $i }}</th> <!-- Menampilkan nilai hari -->
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roster as $rs)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rs->jabatan }}</td>
                                            <td>{{ $rs->unit_kerja }}</td>
                                            <td>{{ $rs->nik }}</td>
                                            <td>{{ $rs->nama }}</td> <!-- Menampilkan nama pengawas -->

                                            <!-- Menampilkan nilai untuk setiap hari 1 sampai 31 -->
                                            @for ($i = 1; $i <= $jumlahHari; $i++)
                                                <td
                                                @if ($rs->$i == 'S1') style="text-align: center;background-color: #92D050"
                                                @elseif ($rs->$i == 'OFF') style="text-align: center;background-color: #FF0000"
                                                @elseif ($rs->$i == 'CT') style="text-align: center;background-color: #00B0F0"
                                                @elseif ($rs->$i == 'C') style="text-align: center;background-color: #00B0F0"
                                                @elseif ($rs->$i == 'M') style="text-align: center;background-color: #D9D9D9"
                                                @elseif ($rs->$i == 'S') style="text-align: center;background-color: #FFF2CC"
                                                @elseif ($rs->$i == 'R') style="text-align: center;background-color: #F6C3FF"
                                                @else style="text-align: center;background-color: #1C1C1C; color:white" @endif
                                                >
                                                {{ $rs->$i ?? null }}</td> <!-- Mengakses nilai hari dengan $rs->$i -->
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    // range picker
    (function () {
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-5'), {
            buttonClass: 'btn'
        });
    })();

    function downloadExport() {
        const tahun = document.querySelector('[name="tahun"]').value;
        const bulan = document.querySelector('[name="bulan"]').value;

        const url = `{{ route('rosterkerja.export') }}?tahun=${tahun}&bulan=${bulan}`;
        window.location.href = url;
    }

</script>
<script>
    // [ HTML5 Export Buttons ]
    $('#basic-btn').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'print']
    });

    // [ Column Selectors ]
    $('#cbtn-selectors').DataTable({
        dom: 'Bfrtip',
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, ':visible']
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape', // Set orientation menjadi landscape
                pageSize: 'A3', // Ukuran halaman (opsional, default A4)
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis'
        ],
        pageLength: 100
    });

    // [ Excel - Cell Background ]
    $('#excel-bg').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function () {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });

    // [ Custom File (JSON) ]
    $('#pdf-json').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            text: 'JSON',
            action: function (e, dt, button, config) {
                var data = dt.buttons.exportData();
                $.fn.dataTable.fileSave(new Blob([JSON.stringify(data)]), 'Export.json');
            }
        }]
    });

</script>

