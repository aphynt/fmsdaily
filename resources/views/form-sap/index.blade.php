@include('layout.head', ['title' => 'Laporan Harian Pengawas Batu Bara'])
@include('layout.sidebar')
@include('layout.header')
<style>
    .center-checkbox {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    @media (min-width: 769px) {

        .tab-pane .form-control,
        .tab-pane .form-select {
            font-size: 9pt;
            padding: 6px;
        }

        .tab-pane button {
            font-size: 9pt;
            padding: 6px;
        }

        .table tbody td,
        .table thead th {
            font-size: 9pt;
            padding: 6px;
        }
    }

    @media (max-width: 768px) {

        .tab-pane .form-control,
        .tab-pane .form-select {
            font-size: 9pt;
            padding: 6px;
        }

        .tab-pane button {
            font-size: 9pt;
            padding: 6px;
        }

        .table tbody td,
        .table thead th {
            font-size: 9pt;
            padding: 6px;
        }

        .description-text {
            word-wrap: break-word;
            white-space: normal;
            max-width: 100%;
            overflow-wrap: break-word;
        }

    }
</style>

<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-md-10 col-xxl-9 mb-4">
                <div class="col-sm-12 col-md-6 col-xxl-4 justify-content-center">
                    <h3>Inspeksi</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form id="laporanForm" enctype="multipart/form-data">
                            @csrf
                            <!-- Lokasi -->
                            <div class="mb-3">
                                <label class="form-label">Shift:</label>
                                <select class="form-select" id="exampleFormControlSelect2" name="shift" required>
                                    <option selected disabled>Pilih shift</option>
                                    @foreach ($shift as $sh)
                                    <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Area:</label>
                                <select class="form-select" id="exampleFormControlSelect2" name="area" required>
                                    <option selected disabled>Pilih area</option>
                                    @foreach ($area as $ar)
                                    <option value="{{ $ar->id }}">{{ $ar->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Jam Kejadian</label>
                                <input type="text" id="pc-timepicker-1" class="form-control" value="" name="jamKejadian">
                            </div>
                            <hr>
                            <!-- Temuan -->
                            <div class="mb-3">
                                <label class="form-label">Temuan KTA/TTA:</label>
                                <textarea class="form-control" placeholder="Masukkan Temuan" name="temuan" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Temuan:</label>
                                <input type="file" class="form-control" name="file_temuan[]" multiple/>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Risiko:</label>
                                <textarea class="form-control" placeholder="Masukkan Risiko" name="risiko" required></textarea>
                            </div>

                            <!-- Pengendalian -->
                            <div class="mb-3">
                                <label class="form-label">Pengendalian:</label>
                                <textarea class="form-control" placeholder="Masukkan Pengendalian" name="pengendalian" required></textarea>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Tindak Lanjut</label>
                                <textarea class="form-control" placeholder="Masukkan Temuan" name="tindakLanjut"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Bukti Tindak Lanjut:</label>
                                <input type="file" class="form-control" name="file_tindakLanjut[]" multiple/>
                            </div>

                            <!-- Risiko -->


                            <!-- File Upload -->


                            <!-- Submit Button -->
                            <div class="text-center m-t-20">
                                <button type="submit" class="badge bg-success" style="font-size:14px" id="submitSAP">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- [ file-upload ] end -->
        </div><!-- [ Main Content ] end -->
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

            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('form-pengawas-sap.post') }}",
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
                            window.location.href = "{{ route('form-pengawas-sap.show') }}";
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
        });
    });
</script>
