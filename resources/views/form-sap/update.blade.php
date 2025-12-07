@include('layout.head', ['title' => 'Update Laporan SAP'])
@include('layout.sidebar')
@include('layout.header')
<style>
    table {
        page-break-inside: auto;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
        -fs-table-paginate: paginate;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    table tr td,
    table tr th {
        font-size: small;
    }

    .header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        border-bottom: 2px solid #000;
        padding: .3rem;
    }

    .header img {
        vertical-align: middle;
    }

    .header .title {
        display: inline-block;
        margin-left: 10px;
        text-align: left;
    }

    .header .title h1 {
        margin: 0;
        font-size: 18px;
        color: #0000FF;
    }

    .header .title p {
        margin: 0;
        font-size: 12px;
    }

    .header .doc-number {
        text-align: right;
        font-size: 12px;
    }

    .info-table,
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2px;
    }

    .info-table td {
        padding: 5px;
        width: 20pt;
    }

    .info-table td:first-child {
        width: 15%;
    }

    .info-table td:nth-child(2) {
        width: .2%;
    }

    .info-table td:nth-child(3) {
        width: 30%;
        vertical-align: bottom;
    }

    .info-table td:nth-child(4) {
        width: 10%;
        background-color: rgb(255, 255, 255);
    }

    .info-table td:nth-child(5) {
        width: 15%;
        vertical-align: bottom;
    }

    .info-table td:nth-child(6) {
        width: .2%;
    }

    .info-table td:nth-child(7) {
        width: 30%;
    }

    .data-table th,
    .data-table td {
        border: 1px solid #000;
        text-align: center;
    }

    .flex {
        display: flex;
    }

    table.data_table {
        width: 100%;
        border: 1px solid #000;
        table-layout: fixed;
    }

    table.data_table tr td,
    table.data_table tr th {
        text-align: center;
        border: 1px solid #000;
    }

    table.data_table tbody tr td {
        height: 15pt;
    }

    table.table_close {
        width: 100%;
        table-layout: fixed;
    }

    table.table_close tr th {
        height: 15pt;
        padding: .2rem;
    }

    th.noborder {
        border: none;
    }

    hr {
        margin-bottom: 1rem;
    }

    .flex {
        display: flex;
        justify-content: space-between;
    }

    .hor {
        display: flex;
        flex-direction: column;
    }

    h4 {
        margin-bottom: 0px;
    }

    .grid-container {
        display: grid;
        grid-template-columns: 70% 30%;
        gap: 20px;
        margin: 20px;
    }

    .grid-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .grid-table th,
    .grid-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    .grid-table th {
        background-color: #f4f4f4;
    }
</style>

<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- FORM UPDATE --}}
                            <form id="laporanForm"
                                  method="post" action="{{ route('form-pengawas-sap.update', $data['report']->uuid) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="col-12">
                                    <div class="row align-items-center g-3">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('dashboard/assets') }}/images/logo-full.png"
                                                     class="img-fluid"
                                                     alt="images"
                                                     width="200px">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <a href="{{ route('form-pengawas-sap.show') }}">
                                                <span class="badge bg-primary">Kembali</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <h2 style="text-align: center;"><u>LAPORAN SAP PENGAWAS</u></h2>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Tanggal Pelaporan</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ date('d-m-Y', strtotime($data['report']->created_at)) }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Jam Kejadian</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ date('H:i', strtotime($data['report']->jam_kejadian)) }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Shift</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $data['report']->shift ?: '-' }}"
                                               readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Area</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $data['report']->area ?: '-' }}"
                                               readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label>Temuan KTA/TTA</label>
                                        <textarea class="form-control"
                                                  rows="5"
                                                  name="temuan"
                                                  placeholder="Masukkan Temuan">{{ old('temuan', $data['report']->temuan) }}</textarea>
                                    </div>
                                </div>

                                <h4>Foto Temuan</h4>
                                <div class="mb-3">
                                    <input type="file"
                                           class="form-control"
                                           name="file_temuan"
                                            />
                                    @if(!empty($data['report']->file_temuan))
                                        <div class="col-md-4 mt-2">
                                            <img src="{{ $data['report']->file_temuan }}"
                                                 alt="Photo Temuan"
                                                 class="img-thumbnail custom-img">
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="tingkatRisiko" class="form-label">Tingkat Risiko:</label>
                                    <select class="form-select" id="tingkatRisiko" name="tingkatRisiko" required>
                                        <option value="{{ $data['report']->tingkat_risiko }}">{{ $data['report']->tingkat_risiko }}</option>
                                        <option value="Ringan">Ringan</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Risiko</label>
                                    <textarea class="form-control"
                                              rows="5"
                                              name="risiko"
                                              placeholder="Masukkan Risiko">{{ old('risiko', $data['report']->risiko) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Pengendalian</label>
                                    <textarea class="form-control"
                                              rows="5"
                                              name="pengendalian"
                                              placeholder="Masukkan Pengendalian">{{ old('pengendalian', $data['report']->pengendalian) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Tindak Lanjut</label>
                                    {{-- NAMA FIELD SENGAJA DISAMAKAN DENGAN CONTROLLER: $request->tindakLanjut --}}
                                    <textarea class="form-control"
                                              rows="5"
                                              name="tindakLanjut"
                                              placeholder="Masukkan Tindak Lanjut">{{ old('tindakLanjut', $data['report']->tindak_lanjut) }}</textarea>
                                </div>

                                <h4>Foto Tindak Lanjut</h4>
                                <div class="mb-3">
                                    <input type="file"
                                           class="form-control"
                                           name="file_tindakLanjut"
                                            />
                                    @if(!empty($data['report']->file_tindakLanjut))
                                        <div class="col-md-4 mt-2">
                                            <img src="{{ $data['report']->file_tindakLanjut }}"
                                                 alt="Photo Tindak Lanjut"
                                                 class="img-thumbnail custom-img">
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center m-t-20">
                                    <button type="submit"
                                            class="badge bg-dark"
                                            style="font-size:20px"
                                            id="submitSAP">
                                        Finish
                                    </button>
                                </div>
                            </form>
                        </div> {{-- row g-3 --}}
                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>

    const formSAP = document.getElementById('laporanForm');
    const submitSAP = document.getElementById('submitSAP');

    formSAP.addEventListener('submit', function() {
        // Nonaktifkan tombol submit ketika form sedang diproses
        submitSAP.disabled = true;
        submitSAP.innerText = 'Processing...';
        setTimeout(function() {
            submitSAP.disabled = false;
            submitSAP.innerText = 'Submit';
        }, 10000);
    });
</script>
{{-- <script>
    $(document).ready(function () {
        const formSAP   = document.getElementById('laporanForm');
        const submitBtn = document.getElementById('submitSAP');

        $('#laporanForm').on('submit', function (e) {
            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Form ini akan dikirim, pastikan data sudah benar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {

                    submitBtn.disabled  = true;
                    submitBtn.innerText = 'Processing...';

                    const formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('form-pengawas-sap.update', $data['report']->uuid) }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            submitBtn.disabled  = false;
                            submitBtn.innerText = 'Finish';

                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('form-pengawas-sap.show') }}";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function (xhr) {
                            submitBtn.disabled  = false;
                            submitBtn.innerText = 'Finish';

                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseJSON?.message || xhr.responseText || 'Unknown error',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    console.log('Pengguna membatalkan pengiriman form.');
                }
            });
        });
    });
</script> --}}
