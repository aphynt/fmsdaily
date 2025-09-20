@include('layout.head', ['title' => 'Laporan Job Pending'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Laporan Job Pending</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                {{-- <form action="" method="get"> --}}
                                    <div class="input-group" id="pc-datepicker-6">
                                        <input type="text" class="form-control form-control-sm" placeholder="Start date" name="rangeStart" style="max-width: 200px;" id="range-start">
                                        <span class="input-group-text">s/d</span>
                                        <input type="text" class="form-control form-control-sm" placeholder="End date" name="rangeEnd" style="max-width: 200px;" id="range-end">
                                        <button id="refreshButton" class="btn btn-primary btn-sm">Tampilkan</button>
                                    </div>

                                {{-- </form> --}}
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2 text-md-end">
                                <a href="{{ route('jobpending.excelDetail') }}"><span class="badge bg-success" style="font-size:14px"> Excel</span></a>
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
                            <table id="jobPending" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">Tanggal Pending</th>
                                        <th rowspan="2">Shift</th>
                                        <th colspan="2">Pembuat</th>
                                        <th rowspan="2">Lokasi</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Aktivitas</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Elevasi</th>
                                        <th rowspan="2">Issue</th>
                                        <th colspan="2">Penerima</th>

                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>

                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Data dari API akan ditambahkan di sini -->
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
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-6'), {
            buttonClass: 'btn'
        });
    })();

</script>
<script>
    $(document).ready(function() {
        var userRole = "{{ Auth::user()->role }}";
        var table = $('#jobPending').DataTable({

            processing: true,
            serverSide: true,  // Untuk menggunakan server-side processing
            ajax: {
                url: '{{ route('jobpending.apiDetail') }}',  // URL API Anda
                method: 'GET',  // Gunakan GET atau POST sesuai dengan implementasi Anda
                data: function(d) {
                    // Kirimkan parameter tambahan jika diperlukan (misalnya tanggal)
                    var rangeStart = $('#range-start').val();
                    var rangeEnd = $('#range-end').val();
                    d.rangeStart = rangeStart;
                    d.rangeEnd = rangeEnd;
                    delete d.columns;
                    // delete d.search;
                    delete d.order;
                },
            },
            columns: [
                { data: 'date' },
                { data: 'shift' },
                { data: 'nik_pic' },
                { data: 'pic' },
                { data: 'lokasi' },
                { data: 'section' },
                { data: 'aktivitas' },
                { data: 'unit' },
                { data: 'elevasi' },
                {
                    data: 'issue',
                    render: function (data, type, row) {
                        if (!data) return '';
                        return data.length > 100 ? data.substr(0, 50) + '...' : data;
                    }
                },
                { data: 'nik_diterima' },
                { data: 'nama_diterima' },

            ],
            "order": [[0, "desc"]],  // Default sort by first column
            "pageLength": 15,  // Jumlah baris per halaman
            "lengthMenu": [10, 15, 25, 50],  // Pilihan jumlah baris per halaman
        });

        // Event listener untuk tombol refresh
        $('#refreshButton').click(function() {
            table.ajax.reload();  // Reload data dengan AJAX
        });
    });

</script>



