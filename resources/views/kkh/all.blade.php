@include('layout.head', ['title' => 'Daftar Laporan KKH Per Tanggal'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Daftar Laporan KKH Per Tanggal</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row">
                            <div class="col-12 col-md-3 mb-2">
                                <label for="tanggalKKH">Tanggal</label>
                                <input type="text" id="tanggalKKH" class="form-control" name="tanggalKKH">
                            </div>
                            <div class="col-12 col-md-3 mb-2">
                                <label for="cluster">Kategori Operasional</label>
                                <select class="form-select" name="cluster" id="cluster">
                                    <option value="Semua">Semua</option>
                                    <option value="HD">HD</option>
                                    <option value="EX">EX</option>
                                    <option value="Unit Support">Unit Support</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                <button id="cariKKH" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Tampilkan</button>
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
                            <table id="dataKKH" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th rowspan="2">Hari/Tanggal</th>
                                        <th rowspan="2">Jam Pulang</th>
                                        <th colspan="2">Pengisi</th>
                                        <th rowspan="2">Shift</th>
                                        <th colspan="3">Jam Tidur</th>
                                        <th rowspan="2">Jam Berangkat</th>
                                        <th rowspan="2">Fit Bekerja</th>
                                        <th rowspan="2">Keluhan</th>
                                        <th rowspan="2">Masalah Pribadi</th>
                                        <th colspan="2">Verifikasi Pengawas</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Mulai</th>
                                        <th>Bangun</th>
                                        <th>Total</th>
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
            const d_week = new Datepicker(document.querySelector('#tanggalKKH'), {
                buttonClass: 'btn',
                autohide: true,
            });
        })();
    document.addEventListener("DOMContentLoaded", function () {
            const inputTanggal = document.getElementById("tanggalKKH");
            const today = new Date();

            // Format tanggal menjadi YYYY-MM-DD
            const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
                '0')}/${today.getFullYear()}`;
            // Set nilai default input tanggal
            inputTanggal.value = formattedDate;
        });

</script>
<script>
    var table;
    $(document).ready(function() {
        var userRole = "{{ Auth::user()->role }}";
        table = $('#dataKKH').DataTable({


            processing: true,
            serverSide: true,  // Untuk menggunakan server-side processing
            ajax: {
                url: '{{ route('kkh.all_api') }}',  // URL API Anda
                method: 'GET',  // Gunakan GET atau POST sesuai dengan implementasi Anda
                data: function(d) {
                    // Kirimkan parameter tambahan jika diperlukan (misalnya tanggal)
                    var tanggalKKH = $('#tanggalKKH').val();
                    d.tanggalKKH = tanggalKKH;
                    var cluster = $('#cluster').val();
                    d.cluster = cluster;
                    delete d.columns;
                    // delete d.search;
                    delete d.order;
                },
            },
            columns: [
                { data: 'TANGGAL_DIBUAT' },
                { data: 'JAM_PULANG' },
                { data: 'NIK_PENGISI' },
                { data: 'NAMA_PENGISI' },
                { data: 'SHIFT' },
                { data: 'JAM_TIDUR' },
                { data: 'JAM_BANGUN' },
                {
                    data: 'TOTAL_TIDUR',
                    render: function(data, type, row) {
                        if (data === null || data === '') return '-';

                        // Cek nilai data, pastikan jadi angka dulu
                        var nilai = parseFloat(data);
                        var teks = data + ' Jam';

                        if (!isNaN(nilai) && nilai < 6) {
                            return '<span style="color:red;">' + teks + '</span>';
                        }
                        return '<span style="color:green;">' + teks + '</span>';
                    }
                },
                { data: 'JAM_BERANGKAT' },
                {
                    data: 'FIT_BEKERJA',
                    render: function(data, type, row) {
                        // if (data === null || data === '') return '-';

                        // Cek nilai data, pastikan jadi angka dulu
                        var nilai = parseInt(data);

                        if (nilai == 0) {
                            return '<span style="color:red;">TIDAK</span>';
                        }
                        return '<span style="color:green;">YA</span>';
                    }
                },
                { data: 'KELUHAN' },
                { data: 'MASALAH_PRIBADI' },
                { data: 'NIK_PENGAWAS' },
                { data: 'NAMA_PENGAWAS' },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (!row) return '';

                        if (['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT', 'MANAGER'].includes(userRole) && row.ferivikasi_pengawas == false) {
                            let currentUserRole = userRole?.toUpperCase();

                            let jabatanPengawas = row.JABATAN?.toUpperCase();
                            let isOperator = jabatanPengawas === 'OPERATOR';
                            let allowedToVerify = false;

                            // Cegah verifikasi diri sendiri
                            if (jabatanPengawas !== currentUserRole) {
                                // Kasus jika pengawas adalah OPERATOR
                                if (isOperator) {
                                    allowedToVerify = ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'].includes(currentUserRole);
                                } else {
                                    // Aturan berjenjang berdasarkan peran pengawas
                                    switch (jabatanPengawas) {

                                        case 'FOREMAN':
                                            allowedToVerify = ['SUPERVISOR', 'SUPERINTENDENT'].includes(currentUserRole);
                                            break;
                                        case 'SUPERVISOR':
                                            allowedToVerify = ['SUPERINTENDENT'].includes(currentUserRole);
                                            break;
                                        case 'SUPERINTENDENT':
                                            allowedToVerify = ['MANAGER'].includes(currentUserRole);
                                            break;
                                        case 'PJS. SUPERINTENDENT':
                                            allowedToVerify = ['MANAGER'].includes(currentUserRole);
                                            break;
                                        case 'ASISTEN MANAGER':
                                            allowedToVerify = ['MANAGER'].includes(currentUserRole);
                                            break;
                                        default:
                                            // Selain OPERATOR dan role spesifik, bisa diverifikasi oleh FOREMAN, SUPERVISOR, atau SUPERINTENDENT
                                            allowedToVerify = ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT'].includes(currentUserRole);
                                    }
                                }
                            }

                            if (allowedToVerify) {
                                let editUrl = "{{ route('kkh.verifikasi') }}" + "?rowID=" + encodeURIComponent(row.id);
                                return `
                                    <button class="btn-verifikasi badge w-100" data-id="${row.id}" style="font-size:14px;background-color:#001932;color:white;">
                                        Verifikasi
                                    </button>
                                `;
                            }
                        }
                        return '';
                    }
                }

            ],
            "order": [[0, "asc"]],  // Default sort by first column
            "pageLength": 25,  // Jumlah baris per halaman
            "lengthMenu": [10, 15, 25, 50],  // Pilihan jumlah baris per halaman
        });

        // Event listener untuk tombol refresh
        $('#cariKKH').click(function() {
            table.ajax.reload();  // Reload data dengan AJAX
        });
        table.ajax.reload();
    });


    $(document).on('click', '.btn-verifikasi', function (e) {
        e.preventDefault();

        const rowID = $(this).data('id');

        Swal.fire({
            title: 'Verifikasi Data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Verifikasi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('kkh.verifikasi') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        rowID: rowID
                    },
                    success: function(response) {
                        Swal.fire('Terverifikasi!', 'Data berhasil diverifikasi.', 'success');

                        // ✅ Refresh DataTables tanpa reload halaman
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memverifikasi.', 'error');
                    }
                });
            }
        });
    });
</script>

