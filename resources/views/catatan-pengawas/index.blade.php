@include('layout.head', ['title' => 'Catatan Pengawas'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Laporan Harian Pengawas (Catatan Pengawas)</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                <form action="" method="get">
                                    <div class="input-group" id="pc-datepicker-7">
                                        <input type="text" class="form-control form-control-sm" placeholder="Start date" name="rangeStart" style="max-width: 200px;" id="range-start">
                                        <span class="input-group-text">s/d</span>
                                        <input type="text" class="form-control form-control-sm" placeholder="End date" name="rangeEnd" style="max-width: 200px;" id="range-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                                    </div>
                                </form>
                            </div>
                            {{-- <div class="col-sm-12 col-md-2 mb-2 text-md-end text-center">
                                <button type="button" class="btn btn-success w-100 w-md-auto">
                                    <i class="fas fa-download"></i> Unduh
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="cbtn-selectors"
                                class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">Tanggal Pelaporan</th>
                                        <th rowspan="2">Shift</th>
                                        <th rowspan="2">Area</th>
                                        <th rowspan="2">Lokasi</th>
                                        <th colspan="2">Foreman</th>
                                        <th colspan="2">Supervisor</th>
                                        <th colspan="2">Superintendent</th>
                                        <th colspan="2">Jam</th>
                                        <th rowspan="2">Keterangan</th>
                                        <th rowspan="2">Status Draft</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Start</th>
                                        <th>Stop</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($note as $item)
                                        <tr>
                                            <td>{{ $item->tanggal_pelaporan }}</td>
                                            <td>{{ $item->shift }}</td>
                                            <td>{{ $item->area }}</td>
                                            <td>{{ $item->lokasi }}</td>
                                            <td>{{ $item->nik_foreman }}</td>
                                            <td>{{ $item->nama_foreman }}</td>
                                            <td>{{ $item->nik_supervisor }}</td>
                                            <td>{{ $item->nama_supervisor }}</td>
                                            <td>{{ $item->nik_superintendent }}</td>
                                            <td>{{ $item->nama_superintendent }}</td>
                                            <td>{{ $item->jam_start }}</td>
                                            <td>{{ $item->jam_stop }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>
                                                @if ($item->is_draft == true)
                                                    <span style="color:orange">Ya</span>
                                                @else
                                                    <span  style="color:green">Tidak</span>
                                                @endif
                                            </td>
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
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-7'), {
            buttonClass: 'btn'
        });
    })();

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
                pageSize: 'A4', // Ukuran halaman (opsional, default A4)
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis'
        ]
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
