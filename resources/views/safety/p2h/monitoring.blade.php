@include('layout.head', ['title' => 'Monitoring P2H'])
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
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Monitoring P2H Unit</a></li>
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
                                        <th colspan="2">Verifikator Mekanik</th>
                                        <th colspan="2">Verifikator Produksi</th>
                                        <th rowspan="2">Aksi</th>
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
    $(document).ready(function () {
        const userRole = "{{ Auth::user()->role }}";

        const table = $('#dataP2H').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('p2h.api_monitoring') }}',
                method: 'GET',
                data: function (d) {
                    d.tanggalP2H = $('#tanggalP2H').val();
                    d.shiftP2H = $('#shiftP2H').val();
                    delete d.columns;
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
                    render: function (data) {
                        const color = data >= 1 ? 'red' : 'green';
                        return `<span style="color: ${color};">${data}</span>`;
                    }
                },
                {
                    data: 'VERIFIED_MEKANIK',
                    render: function (_, __, row) {
                        if (!row || row.VAL_NOTOK < 1) return ''; // tidak tampil jika VAL_NOTOK < 1
                        return row.VERIFIED_MEKANIK
                            ? `${row.VERIFIED_MEKANIK}`
                            : '<span class="badge bg-warning">Belum diverifikasi</span>';
                    }
                },
                {
                    data: null,
                    render: function (_, __, row) {
                        if (!row || row.VAL_NOTOK < 1) return ''; // tidak tampil jika VAL_NOTOK < 1
                        return row.NAMAMEKANIK
                            ? `${row.NAMAMEKANIK}`
                            : '<span class="badge bg-warning">Belum diverifikasi</span>';
                    }
                },
                {
                    data: 'VERIFIED_FOREMAN',
                    render: function (_, __, row) {
                        if (!row) return '<span class="badge bg-warning">Belum diverifikasi</span>';
                        return row.VERIFIED_FOREMAN || row.VERIFIED_SUPERVISOR || '<span class="badge bg-warning">Belum diverifikasi</span>';
                    }
                },
                {
                    data: null,
                    render: function (_, __, row) {
                        if (!row) return '<span class="badge bg-warning">Belum diverifikasi</span>';
                        return row.NAMAFOREMAN || row.NAMASUPERVISOR || '<span class="badge bg-warning">Belum diverifikasi</span>';
                    }
                },
                {
                    data: null,
                    render: function (_, __, row) {
                        if (!row) return '';

                        const mekanikRoles = [
                            'FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK',
                            'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK',
                            'LEADER MEKANIK'
                        ];

                        const excludedRoles = [
                            'ADMIN', 'MANAGER',
                            'SUPERINTENDENT', 'SUPERINTENDENT SAFETY',
                            'SUPERVISOR SAFETY', 'FOREMAN SAFETY'
                        ];

                        const editUrl = `{{ route('p2h.detail_monitoring') }}?VHC_ID=${encodeURIComponent(row.VHC_ID)}&OPR_REPORTTIME=${encodeURIComponent(row.OPR_REPORTTIME)}&MTR_HOURMETER=${encodeURIComponent(row.MTR_HOURMETER)}&OPR_NRP=${encodeURIComponent(row.OPR_NRP)}`;

                            return `
                                    <a href="${editUrl}">
                                        <span class="badge w-100" style="font-size:14px;background-color:#198754">
                                            Detail
                                        </span>
                                    </a>
                                `;
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [10, 15, 25, 50]
        });

        // Tombol pencarian manual
        $('#cariP2H').click(function () {
            table.ajax.reload(null, false);
        });

        // Delegated event handler untuk tombol Verifikasi
        $('#dataP2H').on('click', '.btn-verifikasi', function () {
            const btn = $(this);
            const row = {
                VHC_ID: btn.data('vhc_id'),
                OPR_REPORTTIME: btn.data('opr_time'),
                MTR_HOURMETER: btn.data('hm'),
                OPR_NRP: btn.data('nrp')
            };
            verifP2H(row);
        });
    });

    // Fungsi global verifikasi
    window.verifP2H = function (row) {
        // if (!confirm("Yakin ingin memverifikasi data ini?")) return;

        $.ajax({
            url: "{{ route('p2h.verifikasi') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                VHC_ID: row.VHC_ID,
                OPR_REPORTTIME: row.OPR_REPORTTIME,
                MTR_HOURMETER: row.MTR_HOURMETER,
                OPR_NRP: row.OPR_NRP
            },
            success: function (response) {
                // alert("Verifikasi berhasil!");
                $('#dataP2H').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.fire('Gagal', 'Terjadi kesalahan saat memverifikasi.', 'error');
                // alert("Verifikasi gagal: " + xhr.responseText);
            }
        });
    };
</script>

