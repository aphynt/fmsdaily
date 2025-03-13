@include('layout.head', ['title' => 'Monitoring LK & KLKH'])
@include('layout.sidebar')
@include('layout.header')

<style>
    tr.category-row {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: left;
}
tr.category-row td {
    background-color: #f8f9fa;
}
</style>


<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li> --}}
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Monitoring LK & KLKH</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                <form action="" method="get">
                                    <div class="input-group" id="pc-datepicker-5">
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
                                        <th>No</th>
                                        <th>Tanggal Pembuatan</th>
                                        <th>Tanggal Pelaporan</th>
                                        <th>Roster Kerja</th>
                                        <th>Shift Kerja</th>
                                        <th>Jenis Laporan</th>
                                        <th>Laporan</th>
                                        <th>Area</th>
                                        <th>Unit Kerja</th>
                                        <th>Jam Pelaporan</th>
                                        <th>Jabatan</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($combinedQuery as $co)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($co->tanggal_pembuatan)->format('Y-m-d') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($co->tanggal_pelaporan)->format('Y-m-d') }}</td>
                                                <td>{{ $co->roster_kerja }}</td>
                                                <td>{{ $co->shift }}</td>
                                                <td>{{ $co->jenis_laporan }}</td>
                                                <td>{{ $co->source_table }}</td>
                                                <td>{{ $co->pit }}</td>
                                                <td>{{ $co->unit_kerja }}</td>
                                                <td>{{ \Carbon\Carbon::parse($co->jam_pelaporan)->format('H:i') }}</td>
                                                <td>{{ $co->role }}</td>
                                                <td>{{ $co->nik_pic }}</td>
                                                <td>{{ $co->pic }}</td>
                                                <td>
                                                    <a href="
                                                    @if($co->source_table == 'LOADING POINT') {{ route('klkh.loading-point.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'HAUL ROAD') {{ route('klkh.haul-road.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'DISPOSAL/DUMPING POINT') {{ route('klkh.disposal.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'DUMPING DIKOLAM AIR/LUMPUR') {{ route('klkh.lumpur.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'OGS') {{ route('klkh.ogs.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'BATU BARA') {{ route('klkh.batubara.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'SIMPANG EMPAT') {{ route('klkh.simpangempat.preview', $co->uuid) }}@endif
                                                    @if($co->source_table == 'Laporan Kerja Produksi') {{ route('form-pengawas-new.preview', $co->uuid) }}@endif
                                                     " class="avtar avtar-s btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V7H5zm7-2q-2.05 0-3.662-1.112T6 13q.725-1.775 2.338-2.887T12 9t3.663 1.113T18 13q-.725 1.775-2.337 2.888T12 17m0-2.5q-.625 0-1.062-.437T10.5 13t.438-1.062T12 11.5t1.063.438T13.5 13t-.437 1.063T12 14.5m0 1q1.05 0 1.775-.725T14.5 13t-.725-1.775T12 10.5t-1.775.725T9.5 13t.725 1.775T12 15.5"/></svg>
                                                    </a>
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
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-5'), {
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
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis',
            // pageLength: 50
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
<script>
    // Fungsi untuk mengambil parameter query dari URL
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue;
    }

    // Fungsi untuk memformat tanggal menjadi YYYY-MM-DDTHH:MM
    function formatDateToYYYYMMDDHHMM(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    function getShiftTimes() {
        const currentDate = new Date();

        // Shift siang dimulai pukul 06:30 dan berakhir pukul 18:30
        const startDateMorning = new Date(currentDate.setHours(6, 30, 0, 0)); // 06:30:00 hari ini
        const endDateMorning = new Date(currentDate.setHours(18, 30, 0, 0));  // 18:30:00 hari ini

        // Shift malam dimulai pukul 18:30 hari ini dan berakhir pukul 06:30 hari berikutnya
        const startDateNight = new Date(currentDate.setHours(18, 30, 0, 0)); // 18:30:00 hari ini
        const endDateNight = new Date(currentDate.setHours(6, 30, 0, 0)); // 06:30:00 besok

        if (currentDate.getHours() >= 18 && currentDate.getMinutes() >= 30) {
            endDateNight.setDate(endDateNight.getDate() + 1);
            return {
                startDateMorning: formatDateToYYYYMMDDHHMM(startDateNight),
                endDateMorning: formatDateToYYYYMMDDHHMM(endDateNight),
            };
        }else{

            return {
                startDateMorning: formatDateToYYYYMMDDHHMM(startDateMorning),
                endDateMorning: formatDateToYYYYMMDDHHMM(endDateMorning),
            };
        }

        // return {
        //     startDateMorning: formatDateToYYYYMMDDHHMM(startDateMorning),
        //     endDateMorning: formatDateToYYYYMMDDHHMM(endDateMorning),
        // };
    }

    const shiftTimes = getShiftTimes();

    const startDateVerif = getQueryParam('rangeStartVerif', shiftTimes.startDateMorning);
    const endDateVerif = getQueryParam('rangeEndVerif', shiftTimes.endDateMorning);


    // Set nilai default pada input datetime-local
    document.getElementById('range-startverif').value = startDateVerif;
    document.getElementById('range-endverif').value = endDateVerif;
</script>


