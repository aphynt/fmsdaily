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
                        <form id="laporanForm" action="{{ route('form-pengawas-sap.post') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <!-- Lokasi -->
                            <div class="mb-3">
                                <label for="shift" class="form-label">Shift:</label>
                                <select class="form-select" id="shift" name="shift" required>
                                    @foreach ($shift as $sh)
                                        <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="area" class="form-label">Area:</label>
                                <select class="form-select" id="area" name="area" required>
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
                                <!-- pakai accept agar hanya gambar -->
                                <input type="file" class="form-control" name="file_temuan" accept="image/*" capture="environment" required />
                            </div>

                            <div class="mb-3">
                                <label for="tingkatRisiko" class="form-label">Tingkat Risiko:</label>
                                <select class="form-select" id="tingkatRisiko" name="tingkatRisiko" required>
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Risiko:</label>
                                <textarea class="form-control" placeholder="Masukkan Risiko" name="risiko"></textarea>
                            </div>

                            <!-- Pengendalian -->
                            <div class="mb-3">
                                <label class="form-label">Pengendalian:</label>
                                <textarea class="form-control" placeholder="Masukkan Pengendalian" name="pengendalian"></textarea>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Tindak Lanjut</label>
                                <textarea class="form-control" placeholder="Masukkan Temuan" name="tindakLanjut"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Bukti Tindak Lanjut:</label>
                                <input type="file" class="form-control" name="file_tindakLanjut" accept="image/*" capture="environment" />
                            </div>

                            <div class="text-center m-t-20">
                                <button type="submit" class="badge bg-success" style="font-size:20px" id="submitSAP">Posting</button>
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
    document.addEventListener('DOMContentLoaded', function () {
        const formSAP = document.getElementById('laporanForm');
        const submitSAP = document.getElementById('submitSAP');

        function ensureFile(obj, originalName = 'image.jpg') {
            if (!obj) return null;
            if (obj instanceof File) return obj;
            if (obj instanceof Blob) {
                try {
                    return new File([obj], originalName, { type: obj.type || 'image/jpeg', lastModified: Date.now() });
                } catch (err) {

                    obj.name = originalName;
                    return obj;
                }
            }
            return null;
        }

        async function compressFileWithLib(file, options = {}) {
            if (typeof imageCompression === 'undefined') {
                console.warn('imageCompression library not found, skipping compression.');
                return file;
            }
            try {
                const compressed = await imageCompression(file, options);
                return ensureFile(compressed, file.name);
            } catch (err) {
                console.error('Compression error:', err);
                return file;
            }
        }

        function replaceInputFile(inputElement, file) {
            if (!inputElement || !file) return;
            const fileToAdd = ensureFile(file, file.name || 'image.jpg');

            if (!(fileToAdd instanceof File)) {
                console.warn('Cannot convert compressed result to File in this browser; skipping replace for', inputElement.name);
                return;
            }

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(fileToAdd);
            inputElement.files = dataTransfer.files;
        }

        formSAP.addEventListener('submit', async function (e) {
            e.preventDefault();

            submitSAP.disabled = true;
            const originalText = submitSAP.innerText;
            submitSAP.innerText = 'Processing...';


            const safetyTimer = setTimeout(() => {
                submitSAP.disabled = false;
                submitSAP.innerText = originalText;
            }, 30000);

            try {
                const inputTemuan = formSAP.querySelector('input[name="file_temuan"]');
                const inputTindak = formSAP.querySelector('input[name="file_tindakLanjut"]');

                const options = {
                    maxSizeMB: 1.0,
                    maxWidthOrHeight: 1920,
                    useWebWorker: true,
                    initialQuality: 0.75
                };

                let compressedTemuan = null;
                let compressedTindak = null;

                if (inputTemuan && inputTemuan.files && inputTemuan.files.length > 0) {
                    compressedTemuan = await compressFileWithLib(inputTemuan.files[0], options);
                }
                if (inputTindak && inputTindak.files && inputTindak.files.length > 0) {
                    compressedTindak = await compressFileWithLib(inputTindak.files[0], options);
                }

                if (compressedTemuan) replaceInputFile(inputTemuan, compressedTemuan);
                if (compressedTindak) replaceInputFile(inputTindak, compressedTindak);

                // pemberitahuan kecil ke user
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info mt-2';
                alertDiv.innerText = 'Gambar dikompres (client-side). Mengirim form...';
                formSAP.prepend(alertDiv);

                clearTimeout(safetyTimer);
                formSAP.submit();
            } catch (err) {
                console.error('Error during compression/submit:', err);
                clearTimeout(safetyTimer);
                submitSAP.disabled = false;
                submitSAP.innerText = originalText;

                const errDiv = document.createElement('div');
                errDiv.className = 'alert alert-danger mt-2';
                errDiv.innerText = 'Terjadi kesalahan saat memproses gambar. Mengirim form tanpa kompresi.';
                formSAP.prepend(errDiv);

                formSAP.submit();
            }
        });
    });
</script>

