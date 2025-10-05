@include('layout.head', ['title' => 'Laporan Harian Pengawas Pitstop'])
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


<div class="pc-container">
    <div class="pc-content">
        <div>
            <div id="basicwizard" class="form-wizard row justify-content-center">
                <div class="col-sm-12 col-md-6 col-xxl-4 text-center">
                    <h3>Laporan Harian Pengawas Pitstop</h3>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <ul class="nav nav-pills nav-justified">
                                <li class="nav-item" data-target-form="#contactDetailForm"><a href="#contactDetail"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link active"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/menuBB.png"
                                            alt="EX"> <span class="d-none d-sm-inline">Log
                                            On</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#unitPitstopForm"><a href="#unitPitstop"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/supportBB.png" alt="EX">
                                        <span class="d-none d-sm-inline">Unit Pitstop</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#catatanPengawasForm"><a href="#catatanPengawas"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/catatanBB.png"
                                            alt="EX">
                                        <span class="d-none d-sm-inline">Catatan Pengawas</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item"><a href="#finish" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link icon-btn"><img class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/finishBB.png" alt="EX">
                                        <span class="d-none d-sm-inline">Finish</span></a></li>
                                <!-- end nav item -->
                            </ul>
                        </div>
                    </div>
                    @if ($daily != null)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong>
                            Sedang membuat draft Laporan Harian. Jangan lupa selesaikan jika laporan sudah selesai.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('form-pengawas-batubara.post') }}" method="post"
                                onsubmit="return validateForm()" id="submitFormKerja">
                                @csrf
                                <input type="text" style="display: none;" name="uuid" id="uuid" value="{{ old('uuid', $daily['uuid'] ?? '') }}">

                                <div class="tab-content">
                                    <!-- START: Define your progress bar here -->
                                    <div id="bar" class="progress mb-3" style="height: 7px">
                                        <div
                                            class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                        </div>
                                    </div><!-- END: Define your progress bar here -->
                                    <!-- START: Define your tab pans here -->
                                    <div class="tab-pane show active" id="contactDetail">
                                        <div class="text-center">
                                            <h3 class="mb-2">Informasi Dasar</h3>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-sm-6">


                                                        <div class="mb-3"><label class="form-label">Tanggal</label>
                                                            <input type="text" class="form-control" id="pc-datepicker-1"
                                                                name="date" value="{{ old('date', $daily['date'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="shiftID">Shift</label>
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
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="areaID">Area</label>
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
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="nikSupervisor">Supervisor</label>
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

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane" id="unitPitstop">
                                        <div class="text-center">
                                            <h3 class="mb-2">Unit Pitstop</h3>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="mt-2">
                                                <button class="btn btn-primary mb-3" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#tambahPitstopModal">
                                                    <i class="fa-solid fa-add"></i> Tambah Unit Pitstop
                                                </button>
                                                @include('pengawas-pitstop.modal.unit-pitstop')
                                                <div class="accordion" id="accordionPitstop"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="catatanPengawas">
                                        <div class="text-center">
                                            <h3 class="mb-2">Catatan Pengawas</h3>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-sm-12">

                                                        <div class="mb-3"><label class="form-label">Masukkan Catatan</label>
                                                            <textarea class="form-control"
                                                                        id="catatan_pengawas"
                                                                        name="catatan_pengawas"
                                                                        rows="4" style="width:100%; min-height:120px;">{{ old('catatan_pengawas', $daily['catatan_pengawas'] ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- end education detail tab pane -->
                                    <div class="tab-pane" id="finish">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-lg-6">
                                                <div class="text-center"><i
                                                        class="ph-duotone ph-note f-50 text-danger"></i>
                                                    <h3 class="mt-4 mb-3">Terimakasih!</h3>
                                                    <p>Pastikan semua data pada form telah diisi dengan benar sebelum
                                                        melanjutkan ke tahap akhir.</p>
                                                    <div class="mb-3">
                                                        <div class="form-check d-inline-block"><input type="checkbox"
                                                                class="form-check-input" id="customCheck1" checked> <label
                                                                class="form-check-label" for="customCheck1">Saya sudah
                                                                mengisi form ini dengan benar</label></div>
                                                    </div>
                                                    <a href="javascript:void(0);" onclick="saveAsDraft('finish')"><span class="badge bg-success" style="font-size:14px"><i class="fa-solid fa-save"></i> Submit</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex wizard justify-content-end flex-wrap gap-2 mt-5">
                                        <div class="d-flex">
                                            <div id="save_as_draft_id" class="save-as-draft me-2">
                                                <a href="javascript:void(0);" onclick="saveAsDraft('draft')"><span class="badge bg-warning" style="font-size:12px"><i class="fa-solid fa-save"></i> Simpan Draft</span></a>
                                            </div>
                                            <div id="kembaliButton" class="previous me-2">
                                                <a href="javascript:void(0);"><span class="badge bg-secondary" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali</span></a>
                                                {{-- <a href="javascript:void(0);" class="btn btn-secondary btn-md">
                                                    <i class="fa-solid fa-arrow-left"></i> Kembali
                                                </a> --}}
                                            </div>
                                            <div id="lanjutButton" class="next me-3">
                                                <a href="javascript:void(0);"><span class="badge bg-success" style="font-size:12px">Lanjut <i class="fa-solid fa-arrow-right"></i></span></a>
                                                {{-- <a href="javascript:void(0);" class="btn btn-success btn-md">
                                                    Lanjut <i class="fa-solid fa-arrow-right"></i>
                                                </a> --}}
                                            </div>
                                        </div>

                                        <div style="display: none;">
                                            <div class="first me-3">
                                                <a href="javascript:void(0);" class="btn btn-secondary btn-sm">
                                                    <i class="fa-solid fa-arrow-up"></i> Lembar Pertama
                                                </a>
                                            </div>
                                            <div class="last">
                                                <a href="javascript:void(0);" class="btn btn-success btn-sm">
                                                    Finish <i class="fa-solid fa-check"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<!-- untuk save as draft -->
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

            let inputOprSettingan = accordion.querySelector('select[name*="opr_settinganPitstop"]');
            console.log(inputOprSettingan);

            let nikOprSettingan = inputOprSettingan?.value || null;
            let namaOprSettingan = inputOprSettingan?.nextSibling
            ? inputOprSettingan.nextSibling.textContent.trim()
            : null;

            const statusUnitBreakdownPitstop = accordion.querySelector(`input[name*="[status_unit_breakdownPitstop]"]`)?.value || null;
            const statusUnitReadyPitstop = accordion.querySelector(`input[name*="[status_unit_readyPitstop]"]`)?.value || null;
            const statusOprReadyPitstop = accordion.querySelector(`input[name*="[status_opr_readyPitstop]"]`)?.value || null;

            let inputOprReady = accordion.querySelector('select[name*="[opr_readyPitstop]"]:not([name*="status_"])');
            let nikOprReady = inputOprReady?.value || null;
            let namaOprReady = inputOprReady?.nextSibling
            ? inputOprReady.nextSibling.textContent.trim()
            : null;

            const keteranganPitstop = accordion.querySelector(`input[name*="[keteranganPitstop]"]`)?.value || null;


            unitPitstopData.push({
                uuid:uuid,
                nomor_unit: noUnit,
                opr_settingan: nikOprSettingan,
                // nama_opr_settingan: namaOprSettingan,
                status_unit_breakdown: statusUnitBreakdownPitstop,
                status_unit_ready: statusUnitReadyPitstop,
                status_opr_ready: statusOprReadyPitstop,
                opr_ready: nikOprReady,
                // nama_opr_ready: namaOprReady,
                keterangan: keteranganPitstop,
            });

        });
        console.log('Unit yang akan disimpan:', JSON.stringify(unitPitstopData, null, 2));

        formData.append('unit_pitstop', JSON.stringify(unitPitstopData));

        // Catatan Pengawas
        const catatanData = [];

        const catatanAccordions = document.querySelectorAll('#accordionCatatan .accordion-item');

        catatanAccordions.forEach((accordion) => {
        const start = accordion.querySelector(`input[name*="[start_catatan]"]`)?.value || null;
        const end = accordion.querySelector(`input[name*="[end_catatan]"]`)?.value || null;
        const description = accordion.querySelector(`input[name*="[description_catatan]"]`)?.value || null;

            catatanData.push({
                start_catatan: start,
                end_catatan: end,
                description_catatan: description,
            });
        });

        formData.append('catatan', JSON.stringify(catatanData));


            fetch('/save-draft-pengawas-pitstop', {
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
                            window.location.href = "{{ route('pengawas-pitstop.show') }}";
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

    const formKerja = document.getElementById('submitFormKerja');
    const submitButtonKerja = document.getElementById('submitButtonKerja');

    formKerja.addEventListener('submit', function (event) {
        event.preventDefault(); // Mencegah submit default form
        submitButtonKerja.disabled = true;
        submitButtonKerja.innerText = 'Processing...';

        const formData = new FormData();

        // Ambil UUID atau null jika tidak ada
        const uuidElement = document.getElementById('uuid');
        const uuid = uuidElement ? uuidElement.value : null;
        formData.append('uuid', uuid);

        // Logon data
        formData.append('date', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_id', document.querySelector('#shiftID').value);
        formData.append('area_id', document.querySelector('#areaID').value);
        formData.append('catatan_pengawas', document.querySelector('#catatan_pengawas').value);

        // Supervisor
        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);

        // Unit Pitstop
        const unitPitstopData = [];
        const unitPitstopAccordions = document.querySelectorAll('#accordionPitstop .accordion-item');

        //console.log(unitPitstopAccordions);

        unitPitstopAccordions.forEach((accordion) => {

            const noUnit = accordion.querySelector(`input[name*="[no_unitPitstop]"]`)?.value || null;
            let nikOprSettingan = accordion.querySelector('select[name*="opr_settinganPitstop"]')?.value || null;
            let namaOprSettingan = null;

            if (namaOprSettingan && namaOprSettingan.includes('|')) {
                [nikOprSettingan, namaOprSettingan] = namaOprSettingan.split('|');
            }

            const statusUnitBreakdownPitstop = accordion.querySelector(`input[name*="[status_unit_breakdownPitstop]"]`)?.value || null;
            const statusUnitReadyPitstop = accordion.querySelector(`input[name*="[status_unit_readyPitstop]"]`)?.value || null;
            const statusOprReadyPitstop = accordion.querySelector(`input[name*="[status_opr_readyPitstop]"]`)?.value || null;
            let nikOprReady = accordion.querySelector('select[name*="opr_readyPitstop"]')?.value || null;
            let namaOprReady = null;

            if (namaOprReady && namaOprReady.includes('|')) {
                [nikOprReady, namaOprReady] = namaOprReady.split('|');
            }
            const keteranganPitstop = accordion.querySelector(`input[name*="[keteranganPitstop]"]`)?.value || null;


            unitPitstopData.push({
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
        console.log('Data yang akan disimpan:', JSON.stringify(unitPitstopData, null, 2));


        formData.append('unit_pitstop', JSON.stringify(unitPitstopData));


        fetch('/form-pengawas-batubara/post', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                    });
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.error || 'Unknown error');
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: `Error submit laporan: ${error.message}`,
                });
                submitButtonKerja.disabled = false;
                submitButtonKerja.innerText = 'Submit';
            });
    });

</script>

{{-- Script Form Front Loading --}}
<script>
function generateUUID() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}


    const addColumnBtn = document.getElementById('addColumnBtn');
    // const removeColumnBtn = document.getElementById('removeColumnBtn');
    const headerRow1 = document.getElementById('headerRow1');
    const headerRow2 = document.getElementById('headerRow2');
    const tableBody = document.getElementById('tableBody');

    let unitCount = document.querySelectorAll('.unitHeader').length || 1;



</script>



{{-- Script Form unit Pitstop --}}
<script>
    let PitstopCount = 0;

    document.addEventListener("DOMContentLoaded", function () {
        const unitPitstops = @json($unitPitstops);
         // Data dari backend

        const accordionContainer = document.getElementById('accordionPitstop');

        // Render ulang data unitPitstops dari backend
        unitPitstops.forEach((pitstop, index) => {

            const accordionId = `pitstop${index + PitstopCount}`;
            const collapseId = `collapsePitstop${index + PitstopCount}`;


            const accordionItem = `
                <div class="accordion-item" id="${accordionId}" data-loading-id="${pitstop.id}">
                        <h2 class="accordion-header" id="heading${accordionId}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                #${pitstop.no_unit}
                            </button>
                        </h2>
                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionPitstop">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>UUID</th>
                                                <td><input type="hidden" name="unit_pitstop[${index}][uuidPitstop]" value="${pitstop.uuid}">${pitstop.uuid}</td>
                                            </tr>
                                            <tr>
                                                <th>No Unit</th>
                                                <td><input type="hidden" name="unit_pitstop[${index}][no_unitPitstop]" value="${pitstop.no_unit}">${pitstop.no_unit}</td>
                                            </tr>
                                            <tr>
                                                <th>Operator Settingan</th>
                                                <td>
                                                    <select class="form-select"  data-trigger name="unit_pitstop[${index}][opr_settinganPitstop]">
                                                        <option value="${pitstop.opr_settingan ?? ''}|${pitstop.nama_opr_settingan ?? ''}" selected disabled>${pitstop.opr_settingan && pitstop.nama_opr_settingan ? pitstop.opr_settingan + '|' + pitstop.nama_opr_settingan : (pitstop.opr_settingan || pitstop.nama_opr_settingan || '')}</option>
                                                        @foreach ($data['operator'] as $op)
                                                            <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status Unit Breakdown</th>
                                                <td><input type="datetime-local" class="form-control" name="unit_pitstop[${index}][status_unit_breakdownPitstop]" value="${pitstop.status_unit_breakdown ?? ''}"></td>
                                            </tr>
                                            <tr>
                                                <th>Status Unit Ready</th>
                                                <td><input type="datetime-local" class="form-control" name="unit_pitstop[${index}][status_unit_readyPitstop]" value="${pitstop.status_unit_ready ?? ''}"></td>
                                            </tr>
                                            <tr>
                                                <th>Status Operator Ready</th>
                                                <td><input type="datetime-local" class="form-control" name="unit_pitstop[${index}][status_opr_readyPitstop]" value="${pitstop.status_opr_ready ?? ''}"></td>
                                            </tr>
                                            <tr>
                                                <th>Operator (Ready)</th>
                                                <td>
                                                    <select class="form-select"  data-trigger name="unit_pitstop[${index}][opr_readyPitstop]">
                                                        <option value="${pitstop.opr_ready ?? ''}|${pitstop.nama_opr_ready ?? ''}" selected disabled>${pitstop.opr_ready && pitstop.nama_opr_ready ? pitstop.opr_ready + '|' + pitstop.nama_opr_ready : (pitstop.opr_ready || pitstop.nama_opr_ready || '')}</option>
                                                        @foreach ($data['operator'] as $opready)
                                                            <option value="{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}">{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td><input type="text" class="form-control" name="unit_pitstop[${index}][keteranganPitstop]" value="${pitstop.keterangan ?? ''}"></td>
                                            </tr>
                                        </tbody>
                                    </table>


                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removePitstop('${accordionId}')">Hapus</button>
                            </div>
                        </div>
                    </div>`;
                accordionContainer.insertAdjacentHTML('beforeend', accordionItem);
                PitstopCount = index + 1; // Update pitstop count
            });
        });


        document.getElementById('savePitstop').addEventListener('click', () => {
            if (uuid === null) {
                return; // Skip jika uuid tidak ada (data dihapus)
            }


            const noUnit = document.getElementById('no_unitPitstop').value || '';
            let namaOprSettingan = document.getElementById('opr_settinganPitstop').value || "";

            let nikOprSettingan = null;

            if (namaOprSettingan && namaOprSettingan.includes('|')) {
                [nikOprSettingan, namaOprSettingan] = namaOprSettingan.split('|');
            }


            const statusUnitBreakdownPitstop = document.getElementById('status_unit_breakdownPitstop').value || '';
            const statusUnitReadyPitstop = document.getElementById('status_unit_readyPitstop').value || '';
            const statusOprReadyPitstop = document.getElementById('status_opr_readyPitstop').value || '';

            let namaOprReady = document.getElementById('opr_readyPitstop').value || "";
            let nikOprReady = null;

            if (namaOprReady && namaOprReady.includes('|')) {
                [nikOprReady, namaOprReady] = namaOprReady.split('|');
            }

            const keteranganPitstop = document.getElementById('keteranganPitstop').value || '';

            if (!noUnit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'No unit harus diisi!'
                });
                return;
            }

            PitstopCount++;

            const accordionId = `pitstop${PitstopCount}`;
            const collapseId = `collapsePitstop${PitstopCount}`;

            const newAccordionItem = `<div class="accordion-item" id="${accordionId}">
                                            <h2 class="accordion-header" id="heading${accordionId}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                                    #${noUnit}
                                                </button>
                                            </h2>
                                            <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionPitstop">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>UUID</th>
                                                                    <td><input type="hidden" name="unit_pitstop[${PitstopCount-1}][uuidPitstop]" value="${generateUUID()}">${generateUUID()}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>No Unit</th>
                                                                    <td><input type="hidden" name="unit_pitstop[${PitstopCount-1}][no_unitPitstop]" value="${noUnit}">${noUnit}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Operator Settingan</th>
                                                                    <td>
                                                                        <select class="form-select"  data-trigger id="opr_settinganPitstop" name="unit_pitstop[${PitstopCount-1}][opr_settinganPitstop]">
                                                                            <option value="${nikOprSettingan ?? ''}|${namaOprSettingan ?? ''}" selected>${nikOprSettingan && namaOprSettingan ? nikOprSettingan + '|' + namaOprSettingan : (nikOprSettingan || namaOprSettingan || '')}</option>
                                                                            @foreach ($data['operator'] as $op)
                                                                                <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Unit Breakdown</th>
                                                                    <td><input type="datetime-local" class="form-control" name="unit_pitstop[${PitstopCount-1}][status_unit_breakdownPitstop]" value="${statusUnitBreakdownPitstop ?? ''}"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Unit Ready</th>
                                                                    <td><input type="datetime-local" class="form-control" name="unit_pitstop[${PitstopCount-1}][status_unit_readyPitstop]" value="${statusUnitReadyPitstop ?? ''}"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status Operator Ready</th>
                                                                    <td><input type="datetime-local" class="form-control" name="unit_pitstop[${PitstopCount-1}][status_opr_readyPitstop]" value="${statusOprReadyPitstop ?? ''}"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Operator (Ready)</th>
                                                                    <td>
                                                                        <select class="form-select"  data-trigger id="opr_readyPitstop" name="unit_pitstop[${PitstopCount-1}][opr_readyPitstop]">
                                                                            <option value="${nikOprReady ?? ''}|${namaOprReady ?? ''}" selected>${nikOprReady && namaOprReady ? nikOprReady + '|' + namaOprReady : (nikOprReady || namaOprReady || '')}</option>
                                                                            @foreach ($data['operator'] as $opready)
                                                                                <option value="{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}">{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <th>Keterangan</th>
                                                                    <td><input type="text" class="form-control" name="unit_pitstop[${PitstopCount-1}][keteranganPitstop]" value="${keteranganPitstop ?? ''}"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removePitstop('${accordionId}')">Hapus</button>
                                                </div>
                                            </div>
                                        </div>`;

            document.getElementById('accordionPitstop').insertAdjacentHTML('beforeend', newAccordionItem);

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Berhasil ditambahkan, mohon klik Simpan Draft',
                // timer: 2000,
                showConfirmButton: true
            }).then(() => {
                const modalElement = document.getElementById('tambahPitstopModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }


                document.getElementById('no_unitPitstop').value = null;
                document.getElementById('opr_settinganPitstop').value = null;
                document.getElementById('status_unit_breakdownPitstop').value = null;
                document.getElementById('status_unit_readyPitstop').value = null;
                document.getElementById('status_opr_readyPitstop').value = null;
                document.getElementById('opr_readyPitstop').value = null;
                document.getElementById('keteranganPitstop').value = null;
            });
            // document.getElementById("formPitstop").reset();

            // // Reset form setelah data ditambahkan
        });

        // Fungsi untuk menghapus item pitstop
        function removePitstop(accordionId) {
            const unitPitstops = @json($unitPitstops); // Data dari backend


            const item = document.getElementById(accordionId);
            const pitstopId = item ? item.getAttribute('data-loading-id') : null;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    if (pitstopId) {
                        // Jika pitstopId ada, kirim permintaan ke server untuk menghapus data
                        fetch(`/pengawas-pitstop/delete-pitstop/${pitstopId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                            .then((response) => {
                                if (response.ok) {
                                    // Hapus elemen dari DOM jika berhasil
                                    const item = document.getElementById(accordionId);
                                    if (item) {
                                        item.remove();
                                    }
                                    Swal.fire(
                                        'Dihapus!',
                                        'Data berhasil dihapus.',
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Reload halaman setelah penghapusan berhasil
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        'Terjadi kesalahan saat menghapus data.',
                                        'error'
                                    );
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            });
                    } else {
                        // Jika pitstopId tidak ada, hanya hapus elemen dari DOM
                        const item = document.getElementById(accordionId);
                        if (item) {
                            item.remove();
                        }
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            // Jangan reload halaman jika pitstopId tidak ada
                        });
                    }
                }
            });
        }


</script>

{{-- Script Finishing --}}
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


        var checkBox = document.getElementById("customCheck1");
        if (!checkBox.checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap centang kotak untuk menyatakan bahwa Anda sudah mengisi form ini dengan benar.',
                confirmButtonText: 'OK'
            });
            return false;
        }
        return true;
    }

</script>

<script>
    document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
    input.addEventListener('change', function() {
        console.log('Datetime selected:', this.value);
        // picker browser sudah otomatis tertutup
    });
});
</script>
