@include('layout.head', ['title' => 'Daftar P2H'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Daftar P2H Unit</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row">
                            <div class="col-12 col-md-3 mb-2">
                                <label for="tanggalP2H">Tanggal</label>
                                <input type="text" id="tanggalP2H" class="form-control" name="tanggalP2H">
                            </div>
                            <div class="col-12 col-md-3 mb-2">
                                <label for="shiftP2H">Shift</label>
                                <select class="form-select" id="shiftP2H" name="shiftP2H">
                                    @foreach ($data['shift'] as $shh)
                                        <option value="{{ $shh->SHIFTNO }}">{{ $shh->SHIFTDESC }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                <button id="cariP2H" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Tampilkan</button>
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
                            <table id="dataP2H" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">Tanggal Pengisian</th>
                                        <th rowspan="2">NIK Operator</th>
                                        <th rowspan="2">Nama Operator</th>
                                        <th rowspan="2">Not OK</th>
                                        <th colspan="2">Verifikator</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Data dari API akan ditambahkan di sini -->
                                </tbody>
                            </table>
                            {{-- @foreach($support as $item)
                                @include('alat-support.modal.edit', ['item' => $item])
                            @endforeach --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    (function () {
            const d_week = new Datepicker(document.querySelector('#tanggalP2H'), {
                buttonClass: 'btn',
                autohide: true,
            });
        })();
    document.addEventListener("DOMContentLoaded", function () {
            const inputTanggal = document.getElementById("tanggalP2H");
            const today = new Date();

            // Format tanggal menjadi YYYY-MM-DD
            const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
                '0')}/${today.getFullYear()}`;
            // Set nilai default input tanggal
            inputTanggal.value = formattedDate;
        });

</script>
<script>

    $(document).ready(function() {
        var userRole = "{{ Auth::user()->role }}";
        var table = $('#dataP2H').DataTable({


            processing: true,
            serverSide: true,  // Untuk menggunakan server-side processing
            ajax: {
                url: '{{ route('p2h.api') }}',  // URL API Anda
                method: 'GET',  // Gunakan GET atau POST sesuai dengan implementasi Anda
                data: function(d) {
                    // Kirimkan parameter tambahan jika diperlukan (misalnya tanggal)
                    var tanggalP2H = $('#tanggalP2H').val();
                    var shiftP2H = $('#shiftP2H').val();
                    d.tanggalP2H = tanggalP2H;
                    d.shiftP2H = shiftP2H;
                    delete d.columns;
                    // delete d.search;
                    delete d.order;
                },
            },
            columns: [
                { data: 'VHC_ID' },
                { data: 'OPR_REPORTTIME' },
                { data: 'OPR_NRP' },
                { data: 'PERSONALNAME' },
                {
                    data: 'VAL_NOTOK',
                    render: function(data, type, row) {
                        if (data >= 1) {
                            return '<span style="color: red;">' + data + '</span>';
                        } else {
                            return '<span style="color: green;">' + data + '</span>';
                        }
                    }
                },
                {
                    data: 'VERIFIED_FOREMAN',
                    render: function(data, type, row) {
                        // Cek jika row ada (untuk menghindari error)
                        if (!row) return '-';

                        // Jika VERIFIED_FOREMAN ada, tampilkan NAMAFOREMAN, jika null tampilkan NAMA SUPERVISOR, jika keduanya null tampilkan '-'
                        if (row.VERIFIED_FOREMAN) {
                            return row.VERIFIED_FOREMAN ?? '-';
                        } else if (row.VERIFIED_SUPERVISOR) {
                            return row.VERIFIED_SUPERVISOR ?? '-';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // Cek jika row ada (untuk amankan dari error)
                        if (!row) return '-';

                        if (row.VERIFIED_FOREMAN) {
                            return row.NAMAFOREMAN ?? '-';
                        } else if (row.VERIFIED_SUPERVISOR) {
                            return row.NAMASUPERVISOR ?? '-';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        console.log(row);

                        if (!row) return '';

                        if (userRole !== 'SUPERINTENDENT') {
                            if (!row.VERIFIED_FOREMAN && !row.VERIFIED_SUPERVISOR) {
                                if (!(['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK'].includes(userRole) && row.VERIFIED_MEKANIK)) {
                                    let editUrl = "{{ route('p2h.detail') }}" +
                                        "?VHC_ID=" + encodeURIComponent(row.VHC_ID) +
                                        "&OPR_REPORTTIME=" + encodeURIComponent(row.OPR_REPORTTIME) +
                                        "&MTR_HOURMETER=" + encodeURIComponent(row.MTR_HOURMETER) +
                                        "&OPR_NRP=" + encodeURIComponent(row.OPR_NRP);

                                    return `
                                        <a href="${editUrl}">
                                            <span class="badge w-100" style="font-size:14px;background-color:#001932">
                                                Detail
                                            </span>
                                        </a>
                                    `;
                                }
                            }
                        }
                        return '';
                    }
                }


            ],
            "order": [[0, "desc"]],  // Default sort by first column
            "pageLength": 25,  // Jumlah baris per halaman
            "lengthMenu": [10, 15, 25, 50],  // Pilihan jumlah baris per halaman
        });

        // Event listener untuk tombol refresh
        $('#cariP2H').click(function() {
            table.ajax.reload();  // Reload data dengan AJAX
        });
        table.ajax.reload();
    });

</script>

