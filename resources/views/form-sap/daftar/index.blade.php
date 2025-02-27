@include('layout.head', ['title' => 'Daftar Laporan Pengawas Batu Bara'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li> --}}
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Daftar Laporan Pengawas Batu Bara</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                <form action="" method="get">
                                    <div class="input-group" id="pc-datepicker-10">
                                        <input type="text" class="form-control form-control-sm" placeholder="Start date" name="rangeStart" style="max-width: 200px;" id="range-start">
                                        <span class="input-group-text">s/d</span>
                                        <input type="text" class="form-control form-control-sm" placeholder="End date" name="rangeEnd" style="max-width: 200px;" id="range-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                                    </div>
                                </form>
                            </div>
                            @if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER']))
                                <div class="col-sm-12 col-md-2 mb-2 text-md-end">
                                    <a href="{{ route('form-pengawas-batubara.bundlepdf') }}" target="_blank"><span class="badge bg-primary" style="font-size:14px"><i class="fas fa-download"></i> Bundle PDF</span></a>
                                </div>
                            @endif
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
                            <table id="cbtn-selectors" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pelaporan</th>
                                        <th>Jam Kejadian</th>
                                        <th>Shift</th>
                                        <th>NIK</th>
                                        <th>Nama PIC</th>
                                        <th>Area</th>
                                        <th>Temuan KTA/TTA</th>
                                        <th>Risiko</th>
                                        <th>Pengendalian</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($report as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ date('H:i', strtotime($item->jam_kejadian)) }}</td>
                                        <td>{{ $item->shift }}</td>
                                        <td>{{ $item->nik_pic }}</td>
                                        <td>{{ $item->pic }}</td>
                                        <td>{{ $item->area }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->temuan, 20) }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->risiko, 20) }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->pengendalian, 20) }}</td>
                                            <td>
                                                <a href="{{ route('form-pengawas-sap.rincian', $item->uuid) }}"><span class="badge bg-success">Rincian</span></a>
                                                @if (Auth::user()->role == 'ADMIN')
                                                    <a href="#"><span class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#deleteLaporanSAP{{ $item->uuid }}"><i class="fas fa-trash-alt"></i> Hapus</span></a>
                                                @endif
                                            </td>
                                    </tr>
                                    @include('form-sap.delete')
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
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-10'), {
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
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

