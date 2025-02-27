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


<div class="pc-container">
    <div class="pc-content">
        <div>
            <div id="basicwizard" class="form-wizard row justify-content-center">
                <div class="col-sm-12 col-md-6 col-xxl-4 text-center">
                    <h3>Laporan Foreman Batu Bara</h3>
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
                                <li class="nav-item" data-target-form="#loadingPointForm"><a href="#loadingPoint"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/exBB.png"
                                            alt="EX"> <span class="d-none d-sm-inline">Loading Point</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#unitSupportForm"><a href="#unitSupport"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/supportBB.png" alt="EX">
                                        <span class="d-none d-sm-inline">Unit Support</span></a></li>
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
                    @if ($daily == null)
                    <div class="alert alert-dark alert-dismissible fade show" role="alert">
                        <strong>Info!</strong>
                        Belum mengisi Laporan Harian.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @else
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong>Info!</strong>
                        Sedang membuat draft Laporan Harian.
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
                                                                name="tanggal_dasar" value="{{ old('tanggal_dasar', $daily['tanggal_dasar'] ?? '') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="exampleFormControlSelect1">Shift</label>
                                                            <select class="form-select" id="exampleFormControlSelect1"
                                                                onchange="handleChangeShift(this.value)"
                                                                name="shift_dasar">
                                                                <option selected disabled></option>
                                                                @foreach ($data['shift'] as $sh)
                                                                {{-- <option value="{{ $sh->id }}" {{ $daily['shift_dasar_id'] == $sh->id ? 'selected' : '' }}>
                                                                    {{ $sh->keterangan }}
                                                                </option> --}}
                                                                <option value="{{ $sh->id }}" {{ isset($daily) && $daily['shift_dasar_id'] == $sh->id ? 'selected' : '' }}>
                                                                    {{ $sh->keterangan }}
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
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="nikSuperintendent">Superintendent</label>
                                                            <select class="form-select" data-trigger
                                                                id="nikSuperintendent" name="nik_superintendent">
                                                                <option selected disabled></option>
                                                                @foreach ($data['superintendent'] as $st)
                                                                <option value="{{ $st->NRP }}|{{ $st->PERSONALNAME }}"
                                                                    {{ (optional($daily)['nik_superintendent'] ?? null) == ($st->NRP . '|' . $st->PERSONALNAME) ? 'selected' : '' }}>
                                                                    {{ $st->NRP }}|{{ $st->PERSONALNAME }} ({{ $st->JABATAN }})
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- end job detail tab pane -->
                                    <div class="tab-pane" id="loadingPoint">
                                        <div class="text-center">
                                            <h3 class="mb-2">Loading Point</h3>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="mt-2">
                                                <button class="btn btn-primary mb-3" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#tambahLoadingModal">
                                                    <i class="fa-solid fa-add"></i> Tambah Front
                                                </button>
                                                @include('form-pengawas-batubara.modal.loading-point')
                                                <div class="accordion" id="accordionLoading"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="unitSupport">
                                        <div class="text-center">
                                            <h3 class="mb-2">Unit Support</h3>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="mt-2">
                                                <button class="btn btn-primary mb-3" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#tambahSupportModal">
                                                    <i class="fa-solid fa-add"></i> Tambah Unit Support
                                                </button>
                                                @include('form-pengawas-batubara.modal.unit-support')
                                                <div class="accordion" id="accordionSupport"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="catatanPengawas">
                                        <div class="text-center">
                                            <h3 class="mb-2">Catatan Pengawas</h3>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-primary mb-3"
                                                    data-bs-toggle="modal" data-bs-target="#tambahCatatan">
                                                    <i class="fa-solid fa-add"></i> Tambah Catatan
                                                </button>
                                                @include('form-pengawas-batubara.modal.catatan-pengawas')
                                                <div class="accordion" id="accordionCatatan"></div>

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
                                                    <button type="submit" class="btn btn-success" id="submitButtonKerja">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="d-flex wizard justify-content-end flex-wrap gap-2 mt-5">
                                        <div class="d-flex">
                                            <div id="save_as_draft_id" class="save-as-draft me-2">
                                                <a href="javascript:void(0);" onclick="saveAsDraft()"><span class="badge bg-warning" style="font-size:14px"><i class="fa-solid fa-save"></i> Simpan Draft</span></a>
                                            </div>
                                            <div id="kembaliButton" class="previous me-2">
                                                <a href="javascript:void(0);"><span class="badge bg-secondary" style="font-size:14px"><i class="fa-solid fa-arrow-left"></i> Kembali</span></a>
                                                {{-- <a href="javascript:void(0);" class="btn btn-secondary btn-md">
                                                    <i class="fa-solid fa-arrow-left"></i> Kembali
                                                </a> --}}
                                            </div>
                                            <div id="lanjutButton" class="next me-3">
                                                <a href="javascript:void(0);"><span class="badge bg-success" style="font-size:14px">Lanjut <i class="fa-solid fa-arrow-right"></i></span></a>
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
    function saveAsDraft() {
        const formData = new FormData();

        // Ambil UUID atau set null jika tidak ada
        const uuidElement = document.getElementById('uuid');
        const uuid = uuidElement ? uuidElement.value : null;
        formData.append('uuid', uuid);

        // Logon data
        formData.append('tanggal_dasar', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_dasar', document.querySelector('#exampleFormControlSelect1').value);

        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);

        // Ambil Superintendent
        const superintendentSelect = document.querySelector('#nikSuperintendent');
        const superintendentValue = superintendentSelect && superintendentSelect.value !== '' ? superintendentSelect.value : null;
        formData.append('nik_superintendent', superintendentValue);

        // Loading Point
        const loadingPointData = [];
        const loadingPointAccordions = document.querySelectorAll('#accordionLoading .accordion-item');


        loadingPointAccordions.forEach((accordion, index) => {
            let uuid = accordion.querySelector(`input[name="loading_point[${index}][uuidLoading]"]`)?.value || null;
            const subcont = accordion.querySelector(`input[name="loading_point[${index}][subcontLoading]"]`)?.value || null;
            const area = accordion.querySelector(`input[name="loading_point[${index}][areaLoading]"]`)?.value || null;
            const pengawas = accordion.querySelector(`input[name="loading_point[${index}][pengawasLoading]"]`)?.value || null;
            const fleet = accordion.querySelector(`input[name="loading_point[${index}][fleetLoading]"]`)?.value || null;
            const jumlahDT = accordion.querySelector(`input[name="loading_point[${index}][jumlahDTLoading]"]`)?.value || null;
            const seamBB = accordion.querySelector(`input[name="loading_point[${index}][seamBBLoading]"]`)?.value || null;
            const jarak = accordion.querySelector(`input[name="loading_point[${index}][jarakLoading]"]`)?.value || null;
            const keterangan = accordion.querySelector(`input[name="loading_point[${index}][keteranganLoading]"]`)?.value || null;

            loadingPointData.push({
                uuid:uuid,
                subcont: subcont,
                pit: area,
                pengawas: pengawas,
                fleet_ex: fleet,
                jumlah_dt: jumlahDT,
                seam_bb: seamBB,
                jarak: jarak,
                keterangan: keterangan,
            });
        });

        console.log('Data loading point yang akan disimpan:', JSON.stringify(loadingPointData, null, 2));

        formData.append('loading_point', JSON.stringify(loadingPointData));

        // Unit Support
        const unitSupportData = [];
        const unitSupportAccordions = document.querySelectorAll('#accordionSupport .accordion-item');


        unitSupportAccordions.forEach((accordion, index) => {
            let uuid = accordion.querySelector(`input[name="unit_support[${index}][uuidSupport]"]`)?.value || null;
            const jenis = accordion.querySelector(`input[name="unit_support[${index}][jenisSupport]"]`)?.value || null;
            const subcont = accordion.querySelector(`input[name="unit_support[${index}][subcontSupport]"]`)?.value || null;
            const noUnit = accordion.querySelector(`input[name="unit_support[${index}][noUnitSupport]"]`)?.value || null;
            const area = accordion.querySelector(`input[name="unit_support[${index}][areaSupport]"]`)?.value || null;
            const keterangan = accordion.querySelector(`input[name="unit_support[${index}][keteranganSupport]"]`)?.value || null;

            unitSupportData.push({
                uuid:uuid,
                jenis: jenis,
                subcont: subcont,
                nomor_unit: noUnit,
                area: area,
                keterangan,
            });
        });

        console.log('Data unit support yang akan disimpan:', JSON.stringify(unitSupportData, null, 2));

        formData.append('unit_support', JSON.stringify(unitSupportData));

        // Catatan Pengawas
        const catatanData = [];

        const catatanAccordions = document.querySelectorAll('#accordionCatatan .accordion-item');

        catatanAccordions.forEach((accordion) => {
        const start = accordion.querySelector(`input[name$="[start_catatan]"]`)?.value || null;
        const end = accordion.querySelector(`input[name$="[end_catatan]"]`)?.value || null;
        const description = accordion.querySelector(`input[name$="[description_catatan]"]`)?.value || null;

            catatanData.push({
                start_catatan: start,
                end_catatan: end,
                description_catatan: description,
            });
        });

        formData.append('catatan', JSON.stringify(catatanData));


            fetch('/save-draft-form-pengawas-batubara', {
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
                            title: 'Draft Disimpan',
                            text: 'Berhasil menyimpan draft laporan',
                        }).then(() => {
                            location.reload();  // Halaman akan di-reload setelah popup Swal ditutup
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Failed to save draft: ${data.error}`,
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Error saving draft: ${error.message}`,
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
        formData.append('tanggal_dasar', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_dasar', document.querySelector('#exampleFormControlSelect1').value);

        // Supervisor
        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);

        // Superintendent
        const superintendentSelect = document.querySelector('#nikSuperintendent');
        const superintendentValue = superintendentSelect && superintendentSelect.value !== '' ? superintendentSelect.value : null;
        formData.append('nik_superintendent', superintendentValue);

        // Loading Point
        const loadingPointData = [];
        const loadingPointAccordions = document.querySelectorAll('#accordionLoading .accordion-item');

        //console.log(loadingPointAccordions);

        loadingPointAccordions.forEach((accordion, index) => {

            const subcont = accordion.querySelector(`input[name="loading_point[${index}][subcontLoading]"]`)?.value || null;
            const area = accordion.querySelector(`input[name="loading_point[${index}][areaLoading]"]`)?.value || null;
            const pengawas = accordion.querySelector(`input[name="loading_point[${index}][pengawasLoading]"]`)?.value || null;
            const fleet = accordion.querySelector(`input[name="loading_point[${index}][fleetLoading]"]`)?.value || null;
            const jumlahDT = accordion.querySelector(`input[name="loading_point[${index}][jumlahDTLoading]"]`)?.value || null;
            const seamBB = accordion.querySelector(`input[name="loading_point[${index}][seamBBLoading]"]`)?.value || null;
            const jarak = accordion.querySelector(`input[name="loading_point[${index}][jarakLoading]"]`)?.value || null;
            const keterangan = accordion.querySelector(`input[name="loading_point[${index}][keteranganLoading]"]`)?.value || null;

            loadingPointData.push({
                uuid:uuid,
                subcont: subcont,
                pit: area,
                pengawas: pengawas,
                fleet_ex: fleet,
                jumlah_dt: jumlahDT,
                seam_bb: seamBB,
                jarak: jarak,
                keterangan: keterangan,
            });
        });

        formData.append('loading_point', JSON.stringify(unitSupportData));


        // Unit Support
        const unitSupportData = [];
        const unitSupportAccordions = document.querySelectorAll('#accordionSupport .accordion-item');

        //console.log(unitSupportAccordions);

        unitSupportAccordions.forEach((accordion, index) => {
            const unit = accordion.querySelector(`input[name="unit_support[${index}][unitSupport]"]`)?.value || null;

            //sesuaikan lagi untuk menyimpan nama dan nik, karena format nya "0009JKM|Ferdinand L."
            let nama = accordion.querySelector(`input[name="unit_support[${index}][namaSupport]"]`)?.value || null;
            let nik = null;

            if (nama && nama.includes('|')) {
                [nik, nama] = nama.split('|');
            }



            const jenis = accordion.querySelector(`input[name="unit_support[${index}][jenisSupport]"]`)?.value || null;
            const subcont = accordion.querySelector(`input[name="unit_support[${index}][subcontSupport]"]`)?.value || null;
            const noUnit = accordion.querySelector(`input[name="unit_support[${index}][noUnitSupport]"]`)?.value || null;
            const area = accordion.querySelector(`input[name="unit_support[${index}][areaSupport]"]`)?.value || null;
            const keterangan = accordion.querySelector(`input[name="unit_support[${index}][keteranganSupport]"]`)?.value || null;


            unitSupportData.push({
                jenis: jenis,
                subcont: subcont,
                nomor_unit: noUnit,
                area: area,
                keterangan,
            });
        });

        formData.append('unit_support', JSON.stringify(unitSupportData));

        // Catatan Pengawas
        const catatanData = [];
        const catatanAccordions = document.querySelectorAll('#accordionCatatan .accordion-item');
        catatanAccordions.forEach((accordion, index) => {
            const start = accordion.querySelector(`input[name="catatan[${index}][start_catatan]"]`)?.value || null;
            const end = accordion.querySelector(`input[name="catatan[${index}][end_catatan]"]`)?.value || null;
            const description = accordion.querySelector(`input[name="catatan[${index}][description_catatan]"]`)?.value || null;

            catatanData.push({
                start_catatan: start,
                end_catatan: end,
                description_catatan: description,
            });
        }); const catatanAccordions = document.querySelectorAll('#accordionCatatan .accordion-item');

            catatanAccordions.forEach((accordion) => {
            const start = accordion.querySelector(`input[name$="[start_catatan]"]`)?.value || null;
            const end = accordion.querySelector(`input[name$="[end_catatan]"]`)?.value || null;
            const description = accordion.querySelector(`input[name$="[description_catatan]"]`)?.value || null;

            catatanData.push({
                start_catatan: start,
                end_catatan: end,
                description_catatan: description,
            });
        formData.append('catatan', JSON.stringify(catatanData));

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

{{-- Script Form Loading Point --}}
<script>
    let loadingCount = 0;

    document.addEventListener("DOMContentLoaded", function () {
        const loadingPoints = @json($loadingPoints);
        console.log(loadingPoints);
         // Data dari backend

        const accordionContainer = document.getElementById('accordionLoading');

        // Render ulang data loadingPoints dari backend
        loadingPoints.forEach((loading, index) => {

            const accordionId = `loading${index + loadingCount}`;
            const collapseId = `collapseLoading${index + loadingCount}`;

            const subcontText = document.querySelector(`#subcontLoading option[value="${loading.subcont}"]`)?.text.trim() || '';
            const areaText = document.querySelector(`#areaLoading option[value="${loading.pit}"]`)?.text.trim() || '';
            // const pengawasText = document.querySelector(`#pengawasLoading option[value="${loading.pengawas}"]`)?.text.trim() || '';
            // const fleetText = document.querySelector(`#fleetLoading option[value="${loading.fleet_ex}"]`)?.text.trim() || '';

            const accordionItem = `
                <div class="accordion-item" id="${accordionId}" data-loading-id="${loading.id}">
                        <h2 class="accordion-header" id="heading${accordionId}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                #${loading.fleet_ex}
                            </button>
                        </h2>
                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionLoading">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>UUID</th>
                                                <td><input type="hidden" name="loading_point[${index}][uuidLoading]" value="${loading.uuid}">${loading.uuid}</td>
                                            </tr>
                                            <tr>
                                                <th>Subcont</th>
                                                <td><input type="hidden" name="loading_point[${index}][subcontLoading]" value="${loading.subcont}">${subcontText}</td>
                                            </tr>
                                            <tr>
                                                <th>PIT</th>
                                                <td><input type="hidden" name="loading_point[${index}][areaLoading]" value="${loading.pit}">${areaText}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pengawas</th>
                                                <td><input type="hidden" name="loading_point[${index}][pengawasLoading]" value="${loading.pengawas}">${loading.pengawas}</td>
                                            </tr>
                                            <tr>
                                                <th>Fleet EX</th>
                                                <td><input type="hidden" name="loading_point[${index}][fleetLoading]" value="${loading.fleet_ex}">${loading.fleet_ex}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah DT</th>
                                                <td><input type="hidden" name="loading_point[${index}][jumlahDTLoading]" value="${loading.jumlah_dt}">${loading.jumlah_dt}</td>
                                            </tr>
                                            <tr>
                                                <th>Seam BB</th>
                                                <td><input type="hidden" name="loading_point[${index}][seamBBLoading]" value="${loading.seam_bb}">${loading.seam_bb}</td>
                                            </tr>
                                            <tr>
                                                <th>Jarak</th>
                                                <td><input type="hidden" name="loading_point[${index}][jarakLoading]" value="${loading.jarak}">${loading.jarak} km</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td><input type="hidden" name="loading_point[${index}][keteranganLoading]" value="${loading.keterangan}">${loading.keterangan}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeLoading('${accordionId}')">Hapus</button>
                            </div>
                        </div>
                    </div>`;
                accordionContainer.insertAdjacentHTML('beforeend', accordionItem);
                loadingCount = index + 1; // Update loading Point Count
            });
        });


        document.getElementById('saveLoading').addEventListener('click', () => {
            const subcont = document.getElementById('subcontLoading').value || '';
            const textSubcont = document.getElementById('subcontLoading').selectedOptions[0]?.text.trim() || '';
            const pit = document.getElementById('areaLoading').value || '';
            const textPIT = document.getElementById('areaLoading').selectedOptions[0]?.text.trim() || '';
            const pengawas = document.getElementById('pengawasLoading').value || '';
            // const textPengawas = document.getElementById('pengawasLoading').selectedOptions[0]?.text.trim() || '';
            const fleet = document.getElementById('fleetLoading').value || '';
            // const textFleet = document.getElementById('fleetLoading').selectedOptions[0]?.text.trim() || '';

            const jumlahDT = document.getElementById('jumlahDTLoading').value || '';
            const seamBB = document.getElementById('seamBBLoading').value || '';
            const jarak = document.getElementById('jarakLoading').value || '';
            const keterangan = document.getElementById('keteranganLoading').value || '';

            if ( !subcont || !pit || !fleet) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Subcont, area dan fleet harus diisi!'
                });
                return;
            }

            loadingCount++;

            const accordionId = `loading${loadingCount}`;
            const collapseId = `collapseLoading${loadingCount}`;

            const newAccordionItem = `<div class="accordion-item" id="${accordionId}">
                                            <h2 class="accordion-header" id="heading${accordionId}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                                    #${fleet}
                                                </button>
                                            </h2>
                                            <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionLoading">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>UUID</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][uuidLoading]" value="${generateUUID()}">${generateUUID()}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Subcont</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][subcontLoading]" value="${subcont}">${textSubcont}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>PIT</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][areaLoading]" value="${pit}">${textPIT}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nama Pengawas</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][pengawasLoading]" value="${pengawas}">${pengawas}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Fleet EX</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][fleetLoading]" value="${fleet}">${fleet}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jumlah DT</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][jumlahDTLoading]" value="${jumlahDT}">${jumlahDT}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Seam BB</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][seamBBLoading]" value="${seamBB}">${seamBB}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jarak</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][jarakLoading]" value="${jarak}">${jarak} km</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Keterangan</th>
                                                                    <td><input type="hidden" name="loading_point[${loadingCount-1}][keteranganLoading]" value="${keterangan}">${keterangan}</td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeLoading('${accordionId}')">Hapus</button>
                                                </div>
                                            </div>
                                        </div>`;

            document.getElementById('accordionLoading').insertAdjacentHTML('beforeend', newAccordionItem);

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Berhasil ditambahkan, mohon klik Simpan Draft',
                // timer: 2000,
                showConfirmButton: true
            }).then(() => {
                const modalElement = document.getElementById('tambahLoadingModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }

                document.getElementById('jenisSupport').value = null;
                document.getElementById('subcontSupport').value = null;
                document.getElementById('noUnitSupport').value = null;
                document.getElementById('areaSupport').value = null;
                document.getElementById('keteranganSupport').value = null;
            });
            // document.getElementById("formSupport").reset();

            // // Reset form setelah data ditambahkan
        });

        // Fungsi untuk menghapus item support
        function removeLoading(accordionId) {
    //get data from controller with compact: $loadingPoints
            const loadingPoints = @json($loadingPoints); // Data dari backend


            console.log(loadingPoints);

            const item = document.getElementById(accordionId);

            const supportId = item ? item.getAttribute('data-loading-id') : null;

            console.log('Menghapus data support dengan ID:', supportId);
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
                    if (supportId) {
                        console.log('Menghapus data support dengan ID:', supportId);
                        // Jika supportId ada, kirim permintaan ke server
                        fetch(`/batu-bara/delete-loading-point/${supportId}`, {
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
                                        location.reload();
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
                        // Jika supportId tidak ada, cukup hapus elemen dari DOM
                        const item = document.getElementById(accordionId);
                        if (item) {
                            item.remove();
                        }
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                }
            });
    }

</script>

{{-- Script Form unit Support --}}
<script>
    let supportCount = 0;

    document.addEventListener("DOMContentLoaded", function () {
        const unitSupports = @json($unitSupports);
        console.log(unitSupports);
         // Data dari backend

        const accordionContainer = document.getElementById('accordionSupport');

        // Render ulang data unitSupports dari backend
        unitSupports.forEach((support, index) => {

            const accordionId = `support${index + supportCount}`;
            const collapseId = `collapseSupport${index + supportCount}`;
            const namaOperator = `${support.nik_operator}|${support.nama_operator}`;

            const jenisText = document.querySelector(`#jenisSupport option[value="${support.jenis}"]`)?.text.trim() || '';
            const subcontText = document.querySelector(`#subcontSupport option[value="${support.subcont}"]`)?.text.trim() || '';
            // const noUnitText = document.querySelector(`#noUnitSupport option[value="${support.nomor_unit}"]`)?.text.trim() || '';
            const areaText = document.querySelector(`#areaSupport option[value="${support.area}"]`)?.text.trim() || '';

            const accordionItem = `
                <div class="accordion-item" id="${accordionId}" data-loading-id="${support.id}">
                        <h2 class="accordion-header" id="heading${accordionId}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                #${support.nomor_unit}
                            </button>
                        </h2>
                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionSupport">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>UUID</th>
                                                <td><input type="hidden" name="unit_support[${index}][uuidSupport]" value="${support.uuid}">${support.uuid}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis</th>
                                                <td><input type="hidden" name="unit_support[${index}][jenisSupport]" value="${support.jenis}">${jenisText}</td>
                                            </tr>
                                            <tr>
                                                <th>Subcont</th>
                                                <td><input type="hidden" name="unit_support[${index}][subcontSupport]" value="${support.subcont}">${subcontText}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit</th>
                                                <td><input type="hidden" name="unit_support[${index}][noUnitSupport]" value="${support.nomor_unit}">${support.nomor_unit}</td>
                                            </tr>
                                            <tr>
                                                <th>Area</th>
                                                <td><input type="hidden" name="unit_support[${index}][areaSupport]" value="${support.area}">${areaText}</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td><input type="hidden" name="unit_support[${index}][keteranganSupport]" value="${support.keterangan}">${support.keterangan}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeSupport('${accordionId}')">Hapus</button>
                            </div>
                        </div>
                    </div>`;
                accordionContainer.insertAdjacentHTML('beforeend', accordionItem);
                supportCount = index + 1; // Update support count
            });
        });


        document.getElementById('saveSupport').addEventListener('click', () => {
            const jenis = document.getElementById('jenisSupport').value || '';
            const textJenis = document.getElementById('jenisSupport').selectedOptions[0]?.text.trim() || '';
            const subcont = document.getElementById('subcontSupport').value || '';
            const textSubcont = document.getElementById('subcontSupport').selectedOptions[0]?.text.trim() || '';
            const noUnit = document.getElementById('noUnitSupport').value || '';
            // const textNoUnit = document.getElementById('noUnitSupport').selectedOptions[0]?.text.trim() || '';
            const area = document.getElementById('areaSupport').value || '';
            const textArea = document.getElementById('areaSupport').selectedOptions[0]?.text.trim() || '';
            const keterangan = document.getElementById('keteranganSupport').value || '';

            if (!jenis || !subcont || !noUnit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Jenis, Subcont dan No unit harus diisi!'
                });
                return;
            }

            supportCount++;

            const accordionId = `support${supportCount}`;
            const collapseId = `collapseSupport${supportCount}`;

            const newAccordionItem = `<div class="accordion-item" id="${accordionId}">
                                            <h2 class="accordion-header" id="heading${accordionId}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                                    #${noUnit}
                                                </button>
                                            </h2>
                                            <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionSupport">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <th>UUID</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][uuidSupport]" value="${generateUUID()}">${generateUUID()}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jenis</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][jenisSupport]" value="${jenis}">${textJenis}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Subcont</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][subcontSupport]" value="${subcont}">${textSubcont}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nomor Unit</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][noUnitSupport]" value="${noUnit}">${noUnit}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Area</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][areaSupport]" value="${area}">${textArea}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Keterangan</th>
                                                                    <td><input type="hidden" name="unit_support[${supportCount-1}][keteranganSupport]" value="${keterangan}">${keterangan}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSupport('${accordionId}')">Hapus</button>
                                                </div>
                                            </div>
                                        </div>`;

            document.getElementById('accordionSupport').insertAdjacentHTML('beforeend', newAccordionItem);

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Berhasil ditambahkan, mohon klik Simpan Draft',
                // timer: 2000,
                showConfirmButton: true
            }).then(() => {
                const modalElement = document.getElementById('tambahSupportModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }

                document.getElementById('jenisSupport').value = null;
                document.getElementById('subcontSupport').value = null;
                document.getElementById('noUnitSupport').value = null;
                document.getElementById('areaSupport').value = null;
                document.getElementById('keteranganSupport').value = null;
            });
            // document.getElementById("formSupport").reset();

            // // Reset form setelah data ditambahkan
        });

        // Fungsi untuk menghapus item support
        function removeSupport(accordionId) {
    //get data from controller with compact: $unitSupports
            const unitSupports = @json($unitSupports); // Data dari backend


            console.log(unitSupports);

            const item = document.getElementById(accordionId);

            const supportId = item ? item.getAttribute('data-loading-id') : null;

            console.log('Menghapus data support dengan ID:', supportId);
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
                    if (supportId) {
                        console.log('Menghapus data support dengan ID:', supportId);
                        // Jika supportId ada, kirim permintaan ke server
                        fetch(`/batu-bara/delete-support/${supportId}`, {
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
                                        location.reload();
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
                        // Jika supportId tidak ada, cukup hapus elemen dari DOM
                        const item = document.getElementById(accordionId);
                        if (item) {
                            item.remove();
                        }
                        Swal.fire(
                            'Dihapus!',
                            'Data berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                }
            });
    }

</script>

{{-- Script Form Catatan Pengawas --}}
<script>
    let catatanCount = 0;

    document.addEventListener("DOMContentLoaded", function () {
    const catatanPengawas = @json($supervisorNotes);
    const accordionCatatan = document.getElementById('accordionCatatan');

    // Render ulang data catatan pengawas dari backend
        catatanPengawas.forEach((catatan, index) => {
            const accordionId = `catatan${index + 1}`;
            const collapseId = `collapse${index + 1}`;

            const accordionItem = `
                <div class="accordion-item" id="${accordionId}" data-catatan-id="${catatan.id}">
                    <h2 class="accordion-header" id="heading${accordionId}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                            Catatan #${index + 1}
                        </button>
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionCatatan">
                        <div class="accordion-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Start</th>
                                        <td><input type="hidden" name="catatan[${index}][start_catatan]" value="${catatan.jam_start || ''}">${catatan.jam_start ? catatan.jam_start.split('.')[0].substring(0, 5) : ''}</td>
                                    </tr>
                                    <tr>
                                        <th>End</th>
                                        <td><input type="hidden" name="catatan[${index}][end_catatan]" value="${catatan.jam_stop || ''}">${catatan.jam_stop ? catatan.jam_stop.split('.')[0].substring(0, 5) : ''}</td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td style="word-wrap: break-word; white-space: normal; max-width: 100%; overflow-wrap: break-word;">
                                            <input type="hidden" name="catatan[${index}][description_catatan]" value="${catatan.keterangan || ''}">${catatan.keterangan || ''}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="hapusCatatan('${accordionId}')">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            accordionCatatan.insertAdjacentHTML('beforeend', accordionItem);
            catatanCount = index + 1; // Update catatan count
        });
    });


    // Event saat tombol "Tambah" di klik
    document.getElementById('saveCatatan').addEventListener('click', () => {
        const start = document.getElementById('start_catatan').value;
        const end = document.getElementById('end_catatan').value;
        const description = document.getElementById('description_catatan').value;

        // Validasi input
        // if (!start || !end || !description) {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Gagal',
        //         text: 'Semua field harus diisi!'
        //     });
        //     return;
        // }

        if (!description) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Kolom deskripsi harus diisi!'
            });
            return;
        }

        catatanCount++;

        const accordionId = `catatan${catatanCount}`;
        const collapseId = `collapse${catatanCount}`;

        // Template item accordion baru dengan tombol "Hapus"
        const newAccordionItem = `
            <div class="accordion-item" id="${accordionId}">
                <h2 class="accordion-header" id="heading${accordionId}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                        Catatan #${catatanCount}
                    </button>
                </h2>
                <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionCatatan">
                    <div class="accordion-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Start</th>
                                    <td><input type="hidden" name="catatan[${catatanCount-1}][start_catatan]" value="${start}">${start}</td>
                                </tr>
                                <tr>
                                    <th>End</th>
                                    <td><input type="hidden" name="catatan[${catatanCount-1}][end_catatan]" value="${end}">${end}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td style="word-wrap: break-word; white-space: normal; max-width: 100%; overflow-wrap: break-word;">
                                        <input type="hidden" name="catatan[${catatanCount-1}][description_catatan]" value="${description}">${description}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusCatatan('${accordionId}')">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;

        // Tambahkan item baru ke accordion
        document.getElementById('accordionCatatan').insertAdjacentHTML('beforeend', newAccordionItem);

        // Tampilkan notifikasi sukses menggunakan SweetAlert
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Berhasil ditambahkan, mohon klik Simpan Draft',
            // timer: 2000,
            showConfirmButton: true
        }).then(() => {
            // Tutup modal setelah SweetAlert ditutup
            const modalElement = document.getElementById('tambahCatatan');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    });

    // Event listener untuk reset form setelah modal ditutup
    document.getElementById('tambahCatatan').addEventListener('hidden.bs.modal', () => {
        document.getElementById('formCatatan').reset();
    });

    // Fungsi untuk menghapus item accordion
    function hapusCatatan(accordionId) {
        const supervisorNotes = @json($supervisorNotes); // Data dari backend


         console.log(supervisorNotes);

         const item = document.getElementById(accordionId);

         const catatanId = item ? item.getAttribute('data-catatan-id') : null;

        console.log('Menghapus data catatan dengan ID:', catatanId);
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
                if (catatanId) {
                    console.log('Menghapus data catatan dengan ID:', catatanId);
                    // Jika catatanId ada, kirim permintaan ke server
                    fetch(`/batu-bara/delete/catatan-pengawas/${catatanId}`, {
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
                                    location.reload();
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
                    // Jika catatanId tidak ada, cukup hapus elemen dari DOM
                    const item = document.getElementById(accordionId);
                    if (item) {
                        item.remove();
                    }
                    Swal.fire(
                        'Dihapus!',
                        'Data berhasil dihapus.',
                        'success'
                    ).then(() => {
                        location.reload();
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
        const select1 = document.getElementById("exampleFormControlSelect1");
        const select4 = document.getElementById("nikSupervisor");
        const select5 = document.getElementById("nikSuperintendent");


        if (!date.value || !select1.value || !select4.value || !select5.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "Kolom Tanggal, Shift, Area, Unit Kerja, Supervisor dan Superintendent harus diisi",
                confirmButtonText: 'OK'
            });
            return false;
        }

        const frontcheckboxes = document.querySelectorAll('input[name^="front_loading"]');
        let isChecked = false;

        // Cek apakah ada checkbox yang dicentang
        frontcheckboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
            }
        });
        var frontN = document.getElementById("frontUnitNumber");

        if(!frontN.value){
            Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Nomor Unit harus diisi pada form Front Loading',
            confirmButtonText: 'OK'
            });
            return false;
        }
        if(!isChecked){
            Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Harap centang minimal 1 kotak pada form Front Loading',
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
    (function () {
            const d_week = new Datepicker(document.querySelector('#tanggalSupport'), {
                buttonClass: 'btn',
                autohide: true,
            });
        })();
</script>
