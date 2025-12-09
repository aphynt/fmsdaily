@include('layout.head', ['title' => 'Insert Job Pending'])
@include('layout.sidebar')
@include('layout.header')
<style>
    .big-btn {
        font-size: 1.3rem;
        padding: 5px 28px;
    }
</style>
<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-6 col-xxl-4">
                        <h3>Insert Job Pending</h3>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <form action="{{ route('jobpending.post') }}" method="POST" id="submitFormJobPending" enctype="multipart/form-data">
                                @csrf
                                <!-- Inputan di atas tabel -->
                                <div class="row mb-1">

                                    {{-- <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="date">Tanggal Job Pending</label>
                                        <input type="date" class="form-control form-control-sm pb-2" id="date" name="date" required>
                                    </div> --}}
                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="selectShift">Shift</label>
                                        <select class="form-control form-control-sm pb-2" id="selectShift" name="shift" required>
                                            @foreach ($data['shift'] as $sh)
                                                <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="selectSection">Section</label>
                                        <select class="form-control form-control-sm pb-2" id="selectSection" name="section" required>
                                            <option selected disabled></option>
                                            @foreach ($data['section'] as $sec)
                                                <option value="{{ $sec->id }}">{{ $sec->keterangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">

                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="shift">Lokasi</label>
                                        <input type="text" id="lokasi" class="form-control" name="lokasi">
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label>Aktivitas / Unit Support / Elevasi</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle" id="jobTable">
                                                <thead class="table-light text-center">
                                                    <tr>
                                                        <th style="min-width:400px;">Aktivitas/Pekerjaan</th>
                                                        <th style="min-width:150px;">Unit Support</th>
                                                        <th style="min-width:200px;">Elevasi</th>
                                                        <th style="width:80px;">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="aktivitas[]" required></td>
                                                        <td><input type="text" class="form-control" name="unit[]"></td>
                                                        <td><input type="text" class="form-control" name="elevasi[]"></td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm mt-2" id="addRow">
                                            <i class="fa fa-plus"></i> Tambah Row
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-1">
                                    <div class="col-md-12 col-12 px-2 py-2">
                                        <label for="shift">Issue/Catatan</label>
                                        <textarea id="issue" class="form-control" name="issue" rows="4" style="min-height:120px;"></textarea>
                                    </div>

                                </div>
                                <div class="row mb-1">
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="shift">Masukkan Gambar 1 (optional)</label>
                                        <input type="file" id="fileInput" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" name="fileInput">
                                    </div>
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="shift">Masukkan Gambar 2 (optional)</label>
                                        <input type="file" id="fileInput2" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" name="fileInput2">
                                    </div>

                                </div>
                                <div class="row mb-1">
                                    <!-- Preview -->
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <div id="preview"
                                            style="display:flex; justify-content:center; align-items:center;
                                                min-height:300px; border:1px dashed #ccc; border-radius:8px;">
                                            <span style="color:#999;">Preview Gambar 1</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <div id="preview2"
                                            style="display:flex; justify-content:center; align-items:center;
                                                min-height:300px; border:1px dashed #ccc; border-radius:8px;">
                                            <span style="color:#999;">Preview Gambar 2</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <div class="col-md-6 col-12 px-2 py-2">
                                            <label for="rekan">Penerima</label>
                                            <select class="form-select" data-trigger id="rekan" name="rekan">
                                                <option selected disabled></option>
                                                @foreach ($data['rekan'] as $rk)
                                                <option value="{{ $rk->NRP }}">{{ $rk->PERSONALNAME }} ({{ $rk->JABATAN }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}

                               <div class="row text-center">
                                    <div class="mt-2">
                                        <button id="submitButtonJobPending" class="btn btn-primary mb-3 big-btn" type="submit">
                                            <i class="fa-solid fa-paper-plane"></i> Submit
                                        </button>
                                    </div>
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

document.addEventListener("DOMContentLoaded", function () {

    (function setDefaultShift() {
        try {
            let now = new Date();
            let hour = now.getHours();
            let selectedShift = (hour >= 7 && hour < 19) ? "Siang" : "Malam";
            let select = document.getElementById("selectShift");
            if (select) {
                for (let option of select.options) {
                    if (option.text.trim().toLowerCase() === selectedShift.toLowerCase()) {
                        option.selected = true;
                        break;
                    }
                }
            }
        } catch (e) {
            console.warn('setDefaultShift error', e);
        }
    })();

    (function rowHandlers() {
        const tableBody = document.getElementById("jobTable").getElementsByTagName("tbody")[0];
        const addRowBtn = document.getElementById("addRow");

        // Tambah row baru
        if (addRowBtn) {
            addRowBtn.addEventListener("click", function () {
                let newRow = document.createElement("tr");
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="aktivitas[]"></td>
                    <td><input type="text" class="form-control" name="unit[]"></td>
                    <td><input type="text" class="form-control" name="elevasi[]"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm removeRow">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });
        }

        document.addEventListener("click", function (e) {
            if (e.target.closest(".removeRow")) {
                let row = e.target.closest("tr");
                let totalRows = tableBody.rows.length;

                if (totalRows === 1) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak bisa dihapus',
                        text: 'Minimal harus ada 1 row',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    return;
                }

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data row ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus',
                            text: 'Row berhasil dihapus',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    })();


    const input1 = document.getElementById('fileInput');
    const input2 = document.getElementById('fileInput2');
    const preview1 = document.getElementById('preview');
    const preview2 = document.getElementById('preview2');

    const allowedTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];

    function isValidImage(file) {
        if (!file) return false;
        return allowedTypes.includes(file.type);
    }

    function showPreview(container, file, placeholderText = 'Preview Gambar') {
        container.innerHTML = '';
        if (!file) {
            const span = document.createElement('span');
            span.style.color = '#999';
            span.innerText = placeholderText;
            container.appendChild(span);
            return;
        }
        const img = document.createElement('img');
        img.style.maxWidth = '300px';
        img.style.display = 'block';
        img.style.borderRadius = '8px';
        img.src = URL.createObjectURL(file);
        container.appendChild(img);
    }

    function handleLocalPreview(evt, container) {
        const f = evt.target.files && evt.target.files[0];
        if (!f) {
            showPreview(container, null);
            return;
        }
        if (!isValidImage(f)) {
            Swal.fire({
                icon: 'error',
                title: 'File tidak valid!',
                text: 'Hanya gambar (JPG, JPEG, PNG, GIF, WEBP) yang diperbolehkan.'
            });
            evt.target.value = '';
            showPreview(container, null);
            return;
        }
        showPreview(container, f);
    }

    if (input1) input1.addEventListener('change', (e) => handleLocalPreview(e, preview1));
    if (input2) input2.addEventListener('change', (e) => handleLocalPreview(e, preview2));


    function ensureFile(obj, originalName = 'image.jpg') {
        if (!obj) return null;
        if (obj instanceof File) return obj;
        if (obj instanceof Blob) {
            try {
                return new File([obj], originalName, { type: obj.type || 'image/jpeg', lastModified: Date.now() });
            } catch (err) {
                // fallback untuk browser lama
                obj.name = originalName;
                return obj;
            }
        }
        return null;
    }

    async function compressFile(file, options) {
        if (!file) return null;
        if (typeof imageCompression === 'undefined') {
            // library tidak dimuat -> skip compression
            return file;
        }
        try {
            const compressed = await imageCompression(file, options);
            return ensureFile(compressed, file.name);
        } catch (err) {
            console.warn('Compression failed:', err);
            return file;
        }
    }

    function replaceInputFile(inputEl, file) {
        if (!inputEl || !file) return;
        const f = ensureFile(file, file.name || 'image.jpg');
        if (!(f instanceof File)) {
            // tidak bisa mengganti di browser ini
            console.warn('Unable to convert to File for replacement. Skipping replace for', inputEl.id);
            return;
        }
        const dt = new DataTransfer();
        dt.items.add(f);
        inputEl.files = dt.files;
    }


    const form = document.getElementById('submitFormJobPending');
    const submitBtn = document.getElementById('submitButtonJobPending');

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // disable button & UI change
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            // safety timeout
            const safetyTimer = setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 30000);

            try {
                // ambil file saat ini
                const f1 = input1 && input1.files && input1.files[0] ? input1.files[0] : null;
                const f2 = input2 && input2.files && input2.files[0] ? input2.files[0] : null;

                // validasi final
                if (f1 && !isValidImage(f1)) {
                    Swal.fire({ icon:'error', title:'File 1 tidak valid', text:'Hanya gambar yang diperbolehkan.' });
                    clearTimeout(safetyTimer);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    return;
                }
                if (f2 && !isValidImage(f2)) {
                    Swal.fire({ icon:'error', title:'File 2 tidak valid', text:'Hanya gambar yang diperbolehkan.' });
                    clearTimeout(safetyTimer);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    return;
                }

                // compression options (ubah sesuai kebutuhan)
                const options = {
                    maxSizeMB: 1.0,
                    maxWidthOrHeight: 1920,
                    useWebWorker: true,
                    initialQuality: 0.75
                };

                let compressed1 = null;
                let compressed2 = null;

                if (f1) compressed1 = await compressFile(f1, options);
                if (f2) compressed2 = await compressFile(f2, options);

                // replace inputs jika compression menghasilkan File
                if (compressed1) replaceInputFile(input1, compressed1);
                if (compressed2) replaceInputFile(input2, compressed2);

                // update preview ke compressed file
                if (compressed1) showPreview(preview1, compressed1, 'Preview Gambar 1');
                if (compressed2) showPreview(preview2, compressed2, 'Preview Gambar 2');

                // info singkat
                const info = document.createElement('div');
                info.className = 'alert alert-info mt-2';
                info.innerText = 'Gambar dikompres (client-side). Mengirim form...';
                form.prepend(info);

                clearTimeout(safetyTimer);
                form.submit();
            } catch (err) {
                console.error('Error during compress/submit:', err);
                clearTimeout(safetyTimer);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Gagal memproses gambar. Form akan dikirim tanpa kompresi.'
                }).then(() => {
                    form.submit();
                });
            }
        });
    }

});
</script>
