@include('layout.head', ['title' => 'Form Pengawas Pitstop'])
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
                        <h3>Form Pengawas Pitstop</h3>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <form action="{{ route('form-pengawas-pitstop.saveAsFinish') }}" method="post" onsubmit="return validateForm()" id="submitFormKerja">
                                @csrf
                                <input type="text" style="display: none;" name="uuid" id="uuid" value="{{ old('uuid', $daily['uuid'] ?? '') }}">
                                <!-- Inputan di atas tabel -->
                                <div class="row mb-1">

                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="date">Tanggal</label>
                                        <input type="text" class="form-control" id="pc-datepicker-1" name="date" value="{{ old('date', $daily['date'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="selectShift">Shift</label>
                                        <select class="form-select" id="shiftID"
                                            onchange="handleChangeShift(this.value)"
                                            name="shift_id">
                                            <option selected disabled></option>
                                            @foreach ($data['shift'] as $sh)
                                                <option value="{{ $sh->id }}" {{ isset($daily) && $daily['shift_id'] == $sh->id ? 'selected' : '' }}>
                                                    {{ $sh->keterangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="selectArea">Area</label>
                                        <select class="form-select" id="areaID"
                                            onchange="handleChangeShift(this.value)"
                                            name="area_id">
                                            <option selected disabled></option>
                                            @foreach ($data['area'] as $ar)
                                                <option value="{{ $ar->id }}" {{ isset($daily) && $daily['area_id'] == $ar->id ? 'selected' : '' }}>
                                                    {{ $ar->keterangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-6 px-2 py-2">
                                        <label for="nikSupervisor">Supervisor</label>
                                        <select class="form-select" data-trigger id="nikSupervisor"
                                            name="nik_supervisor">
                                            <option selected disabled></option>
                                            @foreach ($data['supervisor'] as $sv)
                                            <option value="{{ $sv->NRP }}|{{ $sv->PERSONALNAME }}"
                                                {{ (optional($daily)['nik_supervisor'] ?? null) == ($sv->NRP . '|' . $sv->PERSONALNAME) ? 'selected' : '' }}>
                                                {{ $sv->NRP }}|{{ $sv->PERSONALNAME }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="min-width: 120px;">Unit</th>
                                                        <th style="min-width: 320px;">Operator Settingan</th>
                                                        <th style="min-width: 180px;">Status Unit Breakdown</th>
                                                        <th style="min-width: 180px;">Status Unit Ready</th>
                                                        <th style="min-width: 180px;">Status Operator Ready</th>
                                                        <th style="min-width: 320px;">Operator Ready</th>
                                                        <th style="min-width: 200px;">Keterangan</th>
                                                        <th style="min-width: 80px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jobContainer">
                                                    <tr class="jobRow">
                                                        <!-- Pilih Unit -->
                                                        <td>
                                                            <select class="form-select" data-trigger name="no_unitPitstop[]">
                                                                <option selected disabled></option>
                                                                @foreach ($data['unit'] as $nu)
                                                                    <option value="{{ $nu->VHC_ID }}">{{ $nu->VHC_ID }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <!-- Operator Settingan -->
                                                        <td>
                                                            <select class="form-select" data-trigger name="opr_settinganPitstop[]">
                                                                <option selected disabled></option>
                                                                @foreach ($data['operator'] as $op)
                                                                    <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <!-- Status Unit Breakdown -->
                                                        <td>
                                                            <input type="datetime-local" class="form-control" name="status_unit_breakdownPitstop[]">
                                                        </td>

                                                        <!-- Status Unit Ready -->
                                                        <td>
                                                            <input type="datetime-local" class="form-control" name="status_unit_readyPitstop[]">
                                                        </td>

                                                        <!-- Status Operator Ready -->
                                                        <td>
                                                            <input type="datetime-local" class="form-control" name="status_opr_readyPitstop[]">
                                                        </td>

                                                        <!-- Operator Ready -->
                                                        <td>
                                                            <select class="form-select" data-trigger name="opr_readyPitstop[]">
                                                                <option selected disabled></option>
                                                                @foreach ($data['operator'] as $op)
                                                                    <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <!-- Keterangan -->
                                                        <td>
                                                            <input type="text" class="form-control" name="keteranganPitstop[]">
                                                        </td>

                                                        <!-- Tombol Hapus -->
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tombol Tambah Row -->
                                        <button type="button" class="btn btn-primary btn-sm mt-2" id="addRow">
                                            <i class="fa fa-plus"></i> Tambah Row
                                        </button>
                                    </div>

                                </div>
                                <hr>
                                <div class="row mb-1">
                                    <div class="col-md-12 col-12 px-2 py-2">
                                        <label for="catatan_pengawas">Catatan</label>
                                        <textarea id="catatan_pengawas" class="form-control" name="catatan_pengawas" rows="4" style="min-height:120px;">{{ old('catatan_pengawas', $daily['catatan_pengawas'] ?? '') }}</textarea>
                                    </div>

                                </div>
                               <div class="row text-center">
                                    <div class="mt-2">
                                        <a href="javascript:void(0);" onclick="saveAsDraft('draft')"><span class="badge bg-warning" style="font-size:12px"><i class="fa-solid fa-save"></i> Simpan Draft</span></a>
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

    $(document).ready(function() {

        // Mengecek tab yang aktif saat halaman dimuat
        checkTabActive();

        // Menambahkan event listener untuk saat tab berubah
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
            checkTabActive();
        });

        function checkTabActive() {
            if ($('#finish').hasClass('active')) {
                $('#lanjutButton').hide();
                $('#save_as_draft_id').hide();
            } else if($('#contactDetail').hasClass('active')){
                $('#kembaliButton').hide();
                $('#lanjutButton').show();
                $('#save_as_draft_id').show();
            }else {
                $('#lanjutButton').show();
                $('#save_as_draft_id').show();
                $('#kembaliButton').show();
            }
        }
    });
</script>

<script>
    function validateForm() {
       const date = document.getElementById("pc-datepicker-1");
        const select1 = document.getElementById("shiftID");
        const select2 = document.getElementById("areaID");
        const select3 = document.getElementById("nikSupervisor");


        if (!date.value || !select1.value || !select2.value || !select3.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "Kolom Tanggal, Shift, Area dan Supervisor harus diisi",
                confirmButtonText: 'OK'
            });
            return false;
        }

        return true;
    }

</script>

<script>
    function saveAsDraft(actionType) {
        if (!validateForm()) {
            return;
        }
        const formData = new FormData();

        // Ambil UUID atau set null jika tidak ada
        const uuidElement = document.getElementById('uuid');
        const uuid = uuidElement ? uuidElement.value : null;
        formData.append('uuid', uuid);
        formData.append('actionType', actionType);

        // Logon data
        formData.append('date', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_id', document.querySelector('#shiftID').value);
        formData.append('area_id', document.querySelector('#areaID').value);
        formData.append('catatan_pengawas', document.querySelector('#catatan_pengawas').value);

        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);


        // Unit Pitstop
        const unitPitstopData = [];
        const unitPitstopAccordions = document.querySelectorAll('#accordionPitstop .accordion-item');


        unitPitstopAccordions.forEach((accordion) => {

            let uuid = accordion.querySelector(`input[name*="[uuidPitstop]"]`)?.value || null;
            const noUnit = accordion.querySelector(`input[name*="[no_unitPitstop]"]`)?.value || null;

            let inputOprSettingan = accordion.querySelector('input[name*="opr_settinganPitstop"]');
            let nikOprSettingan = inputOprSettingan?.value || null;
            let namaOprSettingan = inputOprSettingan?.nextSibling
            ? inputOprSettingan.nextSibling.textContent.trim()
            : null;

            const statusUnitBreakdownPitstop = accordion.querySelector(`input[name*="[status_unit_breakdownPitstop]"]`)?.value || null;
            const statusUnitReadyPitstop = accordion.querySelector(`input[name*="[status_unit_readyPitstop]"]`)?.value || null;
            const statusOprReadyPitstop = accordion.querySelector(`input[name*="[status_opr_readyPitstop]"]`)?.value || null;

            let inputOprReady = accordion.querySelector('input[name*="[opr_readyPitstop]"]:not([name*="status_"])');
            let nikOprReady = inputOprReady?.value || null;
            let namaOprReady = inputOprReady?.nextSibling
            ? inputOprReady.nextSibling.textContent.trim()
            : null;

            const keteranganPitstop = accordion.querySelector(`input[name*="[keteranganPitstop]"]`)?.value || null;


            unitPitstopData.push({
                uuid:uuid,
                nomor_unit: noUnit,
                opr_settingan: nikOprSettingan,
                nama_opr_settingan: namaOprSettingan,
                status_unit_breakdown: statusUnitBreakdownPitstop,
                status_unit_ready: statusUnitReadyPitstop,
                status_opr_ready: statusOprReadyPitstop,
                opr_ready: nikOprReady,
                nama_opr_ready: namaOprReady,
                keterangan: keteranganPitstop,
            });

        });


            fetch('/form-pengawas-pitstop/save-draft', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('uuid').value = data.uuid;

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text:"Saving Success",
                        }).then(() => {
                            if(actionType == 'finish'){
                            window.location.href = "{{ route('form-pengawas-pitstop.show') }}";
                            }else{
                                location.reload();
                            }
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Failed to save: ${data.error}`,
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Error saving: ${error.message}`,
                    });
                });
        }
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




