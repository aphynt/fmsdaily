@include('layout.head', ['title' => 'KLKH'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">KLKH</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                <form action="" method="get">
                                    <div class="input-group" >
                                        <input type="datetime-local" class="form-control form-control-sm" placeholder="Start date" name="rangeStartVerif" style="max-width: 200px;" id="range-startverif">
                                        <span class="input-group-text">s/d</span>
                                        <input type="datetime-local" class="form-control form-control-sm" placeholder="End date" name="rangeEndVerif" style="max-width: 200px;" id="range-endverif">
                                        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
                                    </div>
                                </form>
                            </div>
                            @if (!in_array(Auth::user()->role, ['ADMIN', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY']))
                                <div class="col-sm-12 col-md-2 mb-2 text-md-end">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#alertVerifikasi"><span class="badge bg-success" style="font-size:14px">Verifikasi Semuanya</span></a>
                                </div>
                                @include('klkh.alert')
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
                            <table id="example" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pembuatan</th>
                                        <th>Group</th>
                                        <th>PIC</th>
                                        <th>Pit</th>
                                        <th>Shift</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Supervisor</th>
                                        <th>Superintendent</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($combinedQuery as $category => $items)
                                        @foreach($items as $index => $item)
                                            <tr>
                                                <td>{{ $loop->parent->iteration . '.' . $loop->iteration }}</td>
                                                <td>{{ $item->tanggal_pembuatan }}</td>
                                                <td>{{ $item->source_table }}</td>
                                                <td>{{ $item->pic }}</td>
                                                <td>{{ $item->pit }}</td>
                                                <td>{{ $item->shift }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                <td>{{ date('H:i', strtotime($item->time)) }}</td>
                                                <td>{{ $item->nama_supervisor }}</td>
                                                <td>{{ $item->nama_superintendent }}</td>
                                                <td>
                                                    <a href="
                                                    @if($item->source_table == 'LOADING POINT')
                                                        {{ route('klkh.loading-point.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'HAUL ROAD')
                                                        {{ route('klkh.haul-road.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'DISPOSAL/DUMPING POINT')
                                                        {{ route('klkh.disposal.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'DUMPING DIKOLAM AIR/LUMPUR')
                                                        {{ route('klkh.lumpur.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'OGS')
                                                        {{ route('klkh.ogs.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'BATU BARA')
                                                        {{ route('klkh.batubara.preview', $item->uuid) }}
                                                    @elseif($item->source_table == 'SIMPANG EMPAT')
                                                        {{ route('klkh.simpangempat.preview', $item->uuid) }}
                                                    @endif
                                                     " class="avtar avtar-s btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V7H5zm7-2q-2.05 0-3.662-1.112T6 13q.725-1.775 2.338-2.887T12 9t3.663 1.113T18 13q-.725 1.775-2.337 2.888T12 17m0-2.5q-.625 0-1.062-.437T10.5 13t.438-1.062T12 11.5t1.063.438T13.5 13t-.437 1.063T12 14.5m0 1q1.05 0 1.775-.725T14.5 13t-.725-1.775T12 10.5t-1.775.725T9.5 13t.725 1.775T12 15.5"/></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
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
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-8'), {
            buttonClass: 'btn'
        });
    })();

</script>


<script>
    var groupColumn = 2;
var table = $('#example').DataTable({
    columnDefs: [{ visible: false, targets: groupColumn }],
    order: [[groupColumn, 'asc']],
    displayLength: 10,
    drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        api.column(groupColumn, { page: 'current' })
            .data()
            .each(function (group, i) {
                if (last !== group) {
                    $(rows)
                        .eq(i)
                        .before(
                            '<tr class="group"><td colspan="10"><strong>' +
                                group +
                                '</strong></td></tr>'
                        );

                    last = group;
                }
            });
    }
});

// Order by the grouping
$('#example tbody').on('click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
        table.order([groupColumn, 'desc']).draw();
    }
    else {
        table.order([groupColumn, 'asc']).draw();
    }
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

    console.log(startDateVerif);

    // Set nilai default pada input datetime-local
    document.getElementById('range-startverif').value = startDateVerif;
    document.getElementById('range-endverif').value = endDateVerif;
</script>


