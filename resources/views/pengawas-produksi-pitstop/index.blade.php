@include('layout.head', ['title' => 'Daftar Laporan Pengawas OB & Coal'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Daftar Laporan Pengawas OB & Coal</a></li>
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
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Terakhir Diperbarui</th>
                                        <th rowspan="2">Tanggal</th>
                                        <th rowspan="2">Shift</th>
                                        <th rowspan="2">Area</th>
                                        <th rowspan="2">Unit Kerja</th>
                                        <th rowspan="2">Laporan</th>
                                        <th colspan="2">PIC</th>
                                        <th colspan="2">Foreman</th>
                                        <th colspan="2">Supervisor</th>
                                        <th colspan="2">Superintendent</th>
                                        <th rowspan="2">Draft</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('Y-m-d H:i', strtotime($item->updated_at)) }}</td>
                                            <td>{{ date('Y-m-d', strtotime($item->tanggal)) }}</td>
                                            <td>{{ $item->shift }}</td>
                                            <td>{{ $item->area }}</td>
                                            <td>{{ $item->lokasi }}</td>
                                            <td>{{ $item->unit_kerja }}</td>
                                            <td>{{ $item->nik_pic }}</td>
                                            <td>{{ $item->pic }}</td>
                                            <td>{{ $item->nik_foreman }}</td>
                                            <td>{{ $item->nama_foreman }}</td>
                                            <td>{{ $item->nik_supervisor }}</td>
                                            @if ($item->verified_supervisor == null)
                                                <td>{{ $item->nama_supervisor }} <span class="badge bg-danger">B</span></td>
                                            @else
                                                <td>{{ $item->nama_supervisor }} <span class="badge bg-success">T</span></td>
                                            @endif
                                            <td>{{ $item->nik_superintendent }}</td>
                                            <td>
                                            {{ $item->nama_superintendent }}

                                                @if ($item->unit_kerja == 'OB')
                                                    @if ($item->verified_superintendent == null)
                                                        <span class="badge bg-danger">B</span>
                                                    @else
                                                        <span class="badge bg-success">T</span>
                                                    @endif
                                                @endif
                                            </td>

                                            <td>
                                                @if ($item->is_draft == true)
                                                    <span style="color:orange">Ya</span>
                                                @else
                                                    <span  style="color:green">Tidak</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('form-pengawas-new.download', $item->uuid) }}" target="_blank"><span class="badge bg-primary"><i class="fas fa-print"></i> Cetak</span></a> --}}
                                                <a href="
                                                @if ($item->unit_kerja == 'OB')
                                                    {{ route('form-pengawas-new.preview', $item->uuid) }}
                                                     @else
                                                     {{ route('pengawas-pitstop.preview', $item->uuid) }}
                                                @endif"><span class="badge bg-success">Preview</span></a>

                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <span class="badge bg-success">T</span> : Telah diverifikasi
                        <br>
                        <span class="badge bg-danger">B</span> : Belum diverifikasi
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
        dom:
            "<'row align-items-center g-2 mb-2'"+
            "<'col-12 col-md-6 d-flex flex-wrap align-items-center gap-2'lB>"+
            "<'col-12 col-md-6 text-md-end'f>"+
            ">"+
            "<'row'<'col-12'tr>>"+
            "<'row align-items-center mt-2'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        pageLength: 50,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'Semua']],
        responsive: true,
        autoWidth: false,
        buttons: [
            { extend: 'copyHtml5', exportOptions: { columns: [0, ':visible'] } },
            { extend: 'excelHtml5', exportOptions: { columns: ':visible' } },
            {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14] }
            },
            'colvis',
            'pageLength'
        ],
        language: {
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            search: 'Cari:',
            paginate: { previous: '‹', next: '›' }
        }
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

