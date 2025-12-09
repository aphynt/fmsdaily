@include('layout.head', ['title' => 'Update Laporan SAP'])
@include('layout.sidebar')
@include('layout.header')
<style>
    /* ... (pakai style yang sama seperti milikmu, saya biarkan utuh) ... */
    table { page-break-inside: auto; font-family: 'Times New Roman', Times, serif; font-size: 12px; -fs-table-paginate: paginate; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    table tr td, table tr th { font-size: small; }
    .header { margin-bottom: 20px; display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding: .3rem; }
    .header img { vertical-align: middle; }
    .header .title { display: inline-block; margin-left: 10px; text-align: left; }
    .header .title h1 { margin: 0; font-size: 18px; color: #0000FF; }
    .header .title p { margin: 0; font-size: 12px; }
    .header .doc-number { text-align: right; font-size: 12px; }
    .info-table, .data-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
    .info-table td { padding: 5px; width: 20pt; }
    .info-table td:first-child { width: 15%; }
    .info-table td:nth-child(2) { width: .2%; }
    .info-table td:nth-child(3) { width: 30%; vertical-align: bottom; }
    .info-table td:nth-child(4) { width: 10%; background-color: rgb(255, 255, 255); }
    .info-table td:nth-child(5) { width: 15%; vertical-align: bottom; }
    .info-table td:nth-child(6) { width: .2%; }
    .info-table td:nth-child(7) { width: 30%; }
    .data-table th, .data-table td { border: 1px solid #000; text-align: center; }
    .flex { display: flex; }
    table.data_table { width: 100%; border: 1px solid #000; table-layout: fixed; }
    table.data_table tr td, table.data_table tr th { text-align: center; border: 1px solid #000; }
    table.data_table tbody tr td { height: 15pt; }
    table.table_close { width: 100%; table-layout: fixed; }
    table.table_close tr th { height: 15pt; padding: .2rem; }
    th.noborder { border: none; }
    hr { margin-bottom: 1rem; }
    .flex { display: flex; justify-content: space-between; }
    .hor { display: flex; flex-direction: column; }
    h4 { margin-bottom: 0px; }
    .grid-container { display: grid; grid-template-columns: 70% 30%; gap: 20px; margin: 20px; }
    .grid-table table { width: 100%; border-collapse: collapse; }
    .grid-table th, .grid-table td { border: 1px solid #000; padding: 8px; text-align: center; }
    .grid-table th { background-color: #f4f4f4; }
    .custom-img { max-width: 100%; height: auto; }
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
                                           accept="image/*"
                                           capture="environment" />
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
                                           accept="image/*"
                                           capture="environment" />
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

<!-- jQuery AJAX setup (tetap) -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formSAP = document.getElementById('laporanForm');
    const submitSAP = document.getElementById('submitSAP');

    // convert Blob -> File if needed
    function ensureFile(obj, originalName = 'image.jpg') {
        if (!obj) return null;
        if (obj instanceof File) return obj;
        if (obj instanceof Blob) {
            try {
                return new File([obj], originalName, { type: obj.type || 'image/jpeg', lastModified: Date.now() });
            } catch (err) {
                // older browsers may not support File constructor
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

        // safety timeout (kembalikan tombol kalau stuck)
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

            // replace inputs (hanya jika konversi berhasil jadi File)
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
