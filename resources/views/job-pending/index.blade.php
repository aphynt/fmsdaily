@include('layout.head', ['title' => 'Job Pending'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Job Pending</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row">
                            <div class="col-6 col-md-3 mb-2">
                                <label for="tanggalJobPending">Tanggal</label>
                                <input type="text" id="tanggalJobPending" class="form-control" name="tanggalJobPending">
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <label for="shift">Shift</label>
                                <select class="form-select" name="shift" id="shift">
                                    <option value="Siang">Siang</option>
                                    <option value="Malam">Malam</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                <button id="cariKKH" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Tampilkan</button>
                            </div>
                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                <a href="{{ route('jobpending.insert') }}" class="btn btn-success w-100" style="padding-top:10px;padding-bottom:10px;"><i class="fas fa-plus"></i> Buat Job Pending</a>
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
                                        <th rowspan="2">Tgl Pending</th>
                                        <th rowspan="2">Shift</th>
                                        <th colspan="2">Pembuat</th>
                                        <th rowspan="2">Lokasi</th>
                                        <th colspan="2">Ditujukan ke</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($data as $group)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $group->date }}</td>
                                            <td>{{ $group->shift }}</td>
                                            <td>{{ $group->nik_pic }}</td>
                                            <td>{{ $group->pic }}</td>
                                            <td>{{ $group->lokasi }}</td>
                                            <td>{{ $group->nik_diterima }}</td>
                                            <td>{{ $group->nama_diterima }}</td>
                                            <td>
                                                <a href="{{ route('jobpending.show', $group->uuid) }}" class="badge w-100" style="font-size:14px;background-color:#001932;color:white;">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        {{-- <span class="badge bg-success">T</span> : Telah diketahui
                        <br>
                        <span class="badge bg-danger">B</span> : Belum diketahui --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    (function () {
            const d_week = new Datepicker(document.querySelector('#tanggalJobPending'), {
                buttonClass: 'btn',
                autohide: true,
            });
        })();
    document.addEventListener("DOMContentLoaded", function () {
            const inputTanggal = document.getElementById("tanggalJobPending");
            const today = new Date();

            const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
                '0')}/${today.getFullYear()}`;
            inputTanggal.value = formattedDate;
        });

    document.addEventListener("DOMContentLoaded", function () {
        let now = new Date();
        let hour = now.getHours();

        let selectedShift = (hour >= 7 && hour < 19) ? "Siang" : "Malam";

        let select = document.getElementById("shift");
        for (let option of select.options) {
            if (option.value === selectedShift) {
                option.selected = true;
                break;
            }
        }
    });

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

