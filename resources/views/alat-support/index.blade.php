@include('layout.head', ['title' => 'Alat Support'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Laporan Harian Pengawas (Alat
                                    Support)</a></li>
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
                                <a href="{{ route('alat-support.excel') }}"><span class="badge bg-success" style="font-size:14px"> Excel</span></a>
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
                            <table id="alatSupport" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">Tanggal Pelaporan</th>
                                        <th rowspan="2">Shift</th>
                                        <th rowspan="2">Area</th>
                                        <th rowspan="2">Lokasi</th>
                                        <th rowspan="2">Jenis Unit</th>
                                        <th rowspan="2">Nomor Unit</th>
                                        <th colspan="4">Operator</th>
                                        <th colspan="2">Foreman</th>
                                        <th colspan="2">Supervisor</th>
                                        <th colspan="2">Superintendent</th>
                                        <th colspan="4">HM</th>
                                        <th rowspan="2">Keterangan</th>
                                        <th rowspan="2">Status Draft</th>
                                        <th rowspan="2">Aksi</th>

                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Awal</th>
                                        <th>Akhir</th>
                                        <th>Total</th>
                                        <th>Cash</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Data dari API akan ditambahkan di sini -->
                                </tbody>
                            </table>
                            @foreach($support as $item)
                                @include('alat-support.modal.edit', ['item' => $item])
                            @endforeach
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
        var table = $('#alatSupport').DataTable({

            processing: true,
            serverSide: true,  // Untuk menggunakan server-side processing
            ajax: {
                url: '{{ route('alat-support.api') }}',  // URL API Anda
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
                { data: 'tanggal_pelaporan' },
                { data: 'shift' },
                { data: 'area' },
                { data: 'lokasi' },
                { data: 'jenis_unit' },
                { data: 'nomor_unit' },
                { data: 'nik_operator' },
                { data: 'nama_operator' },
                { data: 'tanggal_operator' },
                { data: 'shift_operator' },
                { data: 'nik_foreman' },
                { data: 'nama_foreman' },
                { data: 'nik_supervisor' },
                { data: 'nama_supervisor' },
                { data: 'nik_superintendent' },
                { data: 'nama_superintendent' },
                { data: 'hm_awal' },
                { data: 'hm_akhir' },
                { data: 'total_hm' },
                { data: 'hm_cash' },
                { data: 'keterangan' },
                {
                    data: 'is_draft',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span style="color: orange;">Ya</span>';
                        } else {
                            return '<span style="color: green;">Tidak</span>';
                        }
                    }
                },


                {
                    data: 'aksi',
                    render: function(data, type, row) {
                        // Hanya tampilkan tombol edit jika peran pengguna adalah Admin
                        if (userRole === 'ADMIN') {
                            var modalId = "editAlatSupport" + row.id + row.uuid;
                            var editUrl = "/alat-support/update/" + row.uuid;
                            return `
                                <a href="#" data-bs-toggle="modal" data-bs-target="#${modalId}">
                                    <span class="badge w-100" style="font-size:14px;background-color:orange">
                                        Edit
                                    </span>
                                </a>
                                ${generateModal(row, editUrl)}
                            `;
                        } else {
                            // Jika bukan admin, tampilkan empty atau string kosong
                            return '-';
                        }
                    }
                }
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
    function generateModal(row, editUrl) {
            return `
                <div class="modal fade" id="editAlatSupport${row.id}${row.uuid}" tabindex="-1" aria-labelledby="modalSupportLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalSupportLabel">Alat Support</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="${editUrl}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nomor Unit</label>
                                        <select class="form-select" name="alat_unit">
                                            <option value="${row.nomor_unit}" selected>${row.nomor_unit}</option>
                                            @foreach ($nomor_unit as $nu)
                                                <option value="{{ $nu->VHC_ID }}">{{ $nu->VHC_ID }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Nama Operator</label>
                                        <select class="form-select" name="nama_operator">
                                            <option value="${row.nik_operator}|${row.nama_operator}" selected>${row.nik_operator}|${row.nama_operator}</option>
                                            @foreach ($operator as $op)
                                                <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tanggal</label>
                                        <input type="date" class="form-control" value="${row.tanggal_operator}" name="tanggal_operator">
                                    </div>
                                    <div class="mb-3">
                                        <label>Shift</label>
                                        <select class="form-select" name="shift_operator">
                                            <option selected value="${row.shift_operator_id}">${row.shift_operator}</option>
                                            @foreach ($shift as $shh)
                                                <option value="{{ $shh->id }}">{{ $shh->keterangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>HM Awal</label>
                                        <input type="text" class="form-control" name="hm_awal" value="${row.hm_awal}">
                                    </div>
                                    <div class="mb-3">
                                        <label>HM Akhir</label>
                                        <input type="text" class="form-control" name="hm_akhir" value="${row.hm_akhir}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Total</label>
                                        <input type="text" class="form-control" name="hm_total" value="${(row.hm_akhir - row.hm_awal).toFixed(2)}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>HM Cash</label>
                                        <input type="text" class="form-control" name="hm_cash" value="${row.hm_cash ? row.hm_cash : ''}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan" value="${row.keterangan ? row.keterangan : ''}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
        }

</script>



