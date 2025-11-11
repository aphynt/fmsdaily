@include('layout.head', ['title' => 'Laporan SAP'])
@include('layout.sidebar')
@include('layout.header')
<style>
    /* body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    } */

    table {
        page-break-inside: auto;
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    }
    table {
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

    table.table_close tr td,
    table.table_close tr th {
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

@php
    // Determine the grid class for imageTemuan
    $imageCountTemuan = count($data['imageTemuan']);
    $gridClassTemuan = ($imageCountTemuan == 3) ? 'col-md-4' : (($imageCountTemuan == 4) ? 'col-md-3' : 'col-md-4');

    // Determine the grid class for imageTindakLanjut
    $imageCountTindakLanjut = count($data['imageTindakLanjut']);
    $gridClassTindakLanjut = ($imageCountTindakLanjut == 3) ? 'col-md-4' : (($imageCountTindakLanjut == 4) ? 'col-md-3' : 'col-md-4');
@endphp

<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <form id="laporanForm" method="post">
                                @csrf
                                <div class="col-12">
                                    <div class="row align-items-center g-3">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('dashboard/assets') }}/images/logo-full.png" class="img-fluid" alt="images" width="200px">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <a href="{{ route('form-pengawas-sap.show') }}"><span class="badge bg-primary">Kembali</span></a>
                                        </div>
                                    </div>
                                </div>
                                <h2 style="text-align: center;"><u>LAPORAN SAP PENGAWAS</u></h2>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Tanggal Pelaporan</label>
                                        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data['report']->created_at)) }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Jam Kejadian</label>
                                        <input type="text" class="form-control" value="{{ date('H:i', strtotime($data['report']->jam_kejadian)) }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Shift</label>
                                        <input type="text" class="form-control" value="{{ $data['report']->shift ? $data['report']->shift : "-" }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Area</label>
                                        <input type="text" class="form-control" value="{{ $data['report']->area ? $data['report']->area : "-" }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Temuan KTA/TTA</label>
                                        <textarea type="text"  class="form-control" rows="5" name="temuan">{{ $data['report']->temuan ? $data['report']->temuan : "-" }}</textarea>
                                    </div>
                                </div>
                                <h4>Foto Temuan</h4>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="file_temuan[]" multiple/>
                                    @foreach ($data['imageTemuan'] as $imageTemuan)
                                        <div class="{{ $gridClassTemuan }} mb-3">
                                            <img src="{{ $imageTemuan->path }}" alt="Photo Temuan" class="img-thumbnail custom-img">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mb-3">
                                    <label>Risiko</label>
                                    <textarea type="text"  class="form-control" rows="5" name="risiko">{{ $data['report']->risiko }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Pengendalian</label>
                                    <textarea type="text"  class="form-control" rows="5" name="pengendalian">{{ $data['report']->pengendalian }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Tindak Lanjut</label>
                                    <textarea type="text"  class="form-control" rows="5" name="tindak_lanjut">{{ $data['report']->tindak_lanjut }}</textarea>
                                </div>
                                <h4>Foto Tindak Lanjut</h4>
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="file_tindakLanjut[]" multiple/>
                                    @foreach ($data['imageTindakLanjut'] as $imageTindakLanjut)
                                        <div class="{{ $gridClassTindakLanjut }} mb-3">
                                            <img src="{{ $imageTindakLanjut->path }}" alt="Photo Kejadian" class="img-thumbnail custom-img">
                                        </div>
                                    @endforeach

                                </div>
                                <div class="text-center m-t-20">
                                    <button type="submit" class="badge bg-dark" style="font-size:20px" id="submitSAP">Finish</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')

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
        }, 7000);
    });
</script>

<script>
    $(document).ready(function () {
        // Ketika form disubmit
        $('#laporanForm').on('submit', function (e) {
            e.preventDefault();

            // Tampilkan alert konfirmasi sebelum submit
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Form ini akan dikirim, pastikan data sudah benar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user menekan 'Ya, Kirim!', maka lanjutkan untuk submit form
                    var formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('form-pengawas-sap.update', $data['report']->uuid) }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // Jika berhasil
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Reload halaman setelah berhasil
                                    location.reload();
                                }
                            });
                            console.log(response);
                        },
                        error: function (xhr, status, error) {
                            // Jika terjadi error
                            Swal.fire({
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseText,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    // Jika user membatalkan, form tidak dikirim
                    console.log('Pengguna membatalkan pengiriman form.');
                }
            });
        });
    });
</script>

