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
                            <form action="{{ route('jobpending.post') }}" method="POST" id="submitFormJobPending">
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
                                        <label>Aktivitas / Unit / Elevasi</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle" id="jobTable">
                                                <thead class="table-light text-center">
                                                    <tr>
                                                        <th style="min-width:400px;">Aktivitas/Pekerjaan</th>
                                                        <th style="min-width:150px;">Unit</th>
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
                                        <label for="shift">Issue</label>
                                        <textarea id="issue" class="form-control" name="issue" rows="4" style="min-height:120px;"></textarea>
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

    const formKLKHLoadingPoint = document.getElementById('submitFormJobPending');
    const submitButtonJobPending = document.getElementById('submitButtonJobPending');

    formKLKHLoadingPoint.addEventListener('submit', function() {
        submitButtonJobPending.disabled = true;
        submitButtonJobPending.innerText = 'Processing...';
        setTimeout(function() {
            submitButtonJobPending.disabled = false;
            submitButtonJobPending.innerText = 'Submit';
        }, 7000);
    });
</script>

<script>
    window.onload = function() {
        var currentDate = new Date();

        var dd = ("0" + currentDate.getDate()).slice(-2);
        var mm = ("0" + (currentDate.getMonth() + 1)).slice(-2);
        var yyyy = currentDate.getFullYear();
        var formattedDate = yyyy + "-" + mm + "-" + dd;

        var hours = ("0" + currentDate.getHours()).slice(-2);
        var minutes = ("0" + currentDate.getMinutes()).slice(-2);
        var formattedTime = hours + ":" + minutes;

        document.getElementById("date").value = formattedDate;
        document.getElementById("time").value = formattedTime;
    }

    document.addEventListener("DOMContentLoaded", function () {
        let now = new Date();
        let hour = now.getHours();

        let selectedShift = (hour >= 7 && hour < 19) ? "Siang" : "Malam";

        let select = document.getElementById("selectShift");
        for (let option of select.options) {
            if (option.text.trim().toLowerCase() === selectedShift.toLowerCase()) {
                option.selected = true;
                break;
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("jobTable").getElementsByTagName("tbody")[0];
        const addRowBtn = document.getElementById("addRow");

        // Tambah row baru
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
            table.appendChild(newRow);
        });

        // Hapus row dengan SweetAlert
        document.addEventListener("click", function (e) {
            if (e.target.closest(".removeRow")) {
                let row = e.target.closest("tr");
                let totalRows = table.rows.length;

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
    });
</script>


