@include('layout.head', ['title' => 'Laporan Harian Pengawas'])
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
                    <h3>Laporan Foreman Produksi</h3>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <ul class="nav nav-pills nav-justified">
                                <li class="nav-item" data-target-form="#contactDetailForm"><a href="#contactDetail"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link active"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/application.png"
                                            alt="EX"> <span class="d-none d-sm-inline">Log
                                            On</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#frontLoadingForm"><a href="#frontLoading"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/excavator-2.png"
                                            alt="EX"> <span class="d-none d-sm-inline">Front
                                            Loading</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#alatSupportForm"><a href="#alatSupport"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/bulldozer.png" alt="EX">
                                        <span class="d-none d-sm-inline">Alat Support</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item" data-target-form="#catatanPengawasForm"><a href="#catatanPengawas"
                                        data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"><img
                                            class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/online-survey.png"
                                            alt="EX">
                                        <span class="d-none d-sm-inline">Catatan Pengawas</span></a></li>
                                <!-- end nav item -->
                                <li class="nav-item"><a href="#finish" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link icon-btn"><img class="pc-icon"
                                            src="{{ asset('dashboard/assets') }}/images/widget/stamp.png" alt="EX">
                                        <span class="d-none d-sm-inline">Finish</span></a></li>
                                <!-- end nav item -->
                            </ul>
                        </div>
                    </div>
                    @if ($daily != null)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Info!</strong>
                            Sedang membuat draft Laporan Harian.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            {{-- <form action="{{ route('form-pengawas-new.post') }}" method="post"
                                onsubmit="return validateForm()" id="submitFormKerja">
                                @csrf --}}
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
                                                                for="exampleFormControlSelect2">Area</label>
                                                            <select class="form-select" id="exampleFormControlSelect2"
                                                                name="area">
                                                                <option selected disabled></option>
                                                                @foreach ($data['area'] as $ar)
                                                                <option value="{{ $ar->id }}" {{ (optional($daily)['area_id'] ?? null) == $ar->id ? 'selected' : '' }}>
                                                                    {{ $ar->keterangan }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3"> <label class="form-label"
                                                                for="exampleFormControlSelect3">Unit Kerja</label>
                                                            <select class="form-select" id="exampleFormControlSelect3"
                                                                name="lokasi">
                                                                <option selected disabled></option>
                                                                @foreach ($data['lokasi'] as $lok)
                                                                    <option value="{{ $lok->id }}" {{ (optional($daily)['lokasi_id'] ?? null) == $lok->id ? 'selected' : '' }}>
                                                                        {{ $lok->keterangan }}
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
                                    <div class="tab-pane" id="frontLoading">
                                        <div class="text-center">
                                            <h3 class="mb-2">Front Loading</h3>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="mt-2">
                                                <button type="button" id="addColumnBtn" class="btn btn-primary mb-3">Tambah Kolom</button>
                                                @php
                                                    $frontUUID = !empty($front_loading) && count($front_loading) > 0 ? $front_loading[count($front_loading) - 1]->uuid : null;
                                                @endphp

                                                <button type="button" onclick="removeColumnBtn('{{ $frontUUID }}')" class="btn btn-danger mb-3">Hapus Kolom</button>
                                                <div class="table-responsive">
                                                    <table id="dynamicTable" class="table table-bordered">
                                                        <thead style="text-align: center; vertical-align: middle;">
                                                            <tr id="headerRow1">
                                                                <th colspan="2" id="thJam">Jam</th>
                                                                @foreach ($front_loading as $index => $loading)
                                                                    <th class="unitHeader" scope="col">Nomor Unit {{ $index + 1 }}</th>
                                                                @endforeach
                                                            </tr>
                                                            <tr id="headerRow2">
                                                                <th id="thSiang">Siang</th>
                                                                <th id="thMalam">Malam</th>
                                                                @foreach ($front_loading as $index => $loading)
                                                                    <th>
                                                                        <select name="front_loading[{{ $index }}][nomor_unit]" id="frontUnitNumber_{{ $index }}" class="form-control">
                                                                            <option value="" disabled {{ empty($loading->nomor_unit) ? 'selected' : '' }}>Pilih</option>
                                                                            @foreach ($data['EX'] as $exa)
                                                                                <option value="{{ $exa->VHC_ID }}"
                                                                                    {{ $loading->nomor_unit == $exa->VHC_ID ? 'selected' : '' }}>
                                                                                    {{ $exa->VHC_ID }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tableBody">

                                                            @foreach ($staticTimeSlots as $slotIndex => $slot)
                                                                <tr>
                                                                    <!-- Waktu Siang -->
                                                                    <td>
                                                                        <input type="hidden" name="staticTimeSlots[{{ $slotIndex }}][siang]" value="{{ $slot['siang'] }}">
                                                                        {{ $slot['siang'] }}
                                                                    </td>
                                                                    <!-- Waktu Malam -->
                                                                    <td>
                                                                        <input type="hidden" name="staticTimeSlots[{{ $slotIndex }}][malam]" value="{{ $slot['malam'] }}">
                                                                        {{ $slot['malam'] }}
                                                                    </td>
                                                                    <!-- Input Data untuk Setiap Unit -->
                                                                    @foreach ($front_loading as $unitIndex => $loading)
                                                                        @php
                                                                            $checked = $loading->checked[$slotIndex] ?? false;
                                                                            $keterangan = $loading->keterangan[$slotIndex] ?? '';
                                                                           $frontloadingUuid = $loading->front_loading_uuid;
                                                                        @endphp
                                                                        <td>
                                                                            <input type="hidden" class="d-none" name="front_loading[{{ $unitIndex }}][time][{{ $slotIndex }}][front_loading_uuid]" value="{{ $frontloadingUuid }}">
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <input type="checkbox" value="true"
                                                                                    name="front_loading[{{ $unitIndex }}][time][{{ $slotIndex }}][checked]"
                                                                                    class="form-check-input"
                                                                                    {{ $checked ? 'checked' : '' }}>
                                                                                <input type="text"
                                                                                    name="front_loading[{{ $unitIndex }}][time][{{ $slotIndex }}][keterangan]"
                                                                                    value="{{ $keterangan }}"
                                                                                    placeholder="Keterangan"
                                                                                    class="form-control">
                                                                            </div>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- end job detail tab pane -->
                                    <div class="tab-pane" id="alatSupport">
                                        <div class="text-center">
                                            <h3 class="mb-2">Alat Support</h3>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="mt-2">
                                                <button class="btn btn-primary mb-3" type="button"
                                                    data-bs-toggle="modal" data-bs-target="#tambahSupportModal">
                                                    <i class="fa-solid fa-add"></i> Tambah Alat Support
                                                </button>
                                                @include('form-pengawas-new.modal.alat-support')
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
                                                @include('form-pengawas-new.modal.catatan-pengawas')
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
                                            <div class="previous me-2" id="kembaliButton">
                                                <a href="javascript:void(0);"><span class="badge bg-secondary" style="font-size:12px"><i class="fa-solid fa-arrow-left"></i> Kembali</span></a>
                                            </div>
                                            <div class="next me-3" id="lanjutButton">
                                                <a href="javascript:void(0);"><span class="badge bg-success" style="font-size:12px" >Lanjut <i class="fa-solid fa-arrow-right"></i></span></a>
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
                            {{-- </form> --}}
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
            return; // Berhenti di sini jika ada notifikasi
        }
        const formData = new FormData();

        // Ambil UUID atau set null jika tidak ada
        const uuidElement = document.getElementById('uuid');
        const uuid = uuidElement ? uuidElement.value : null;
        formData.append('actionType', actionType);
        formData.append('uuid', uuid);

        // Logon data
        formData.append('tanggal_dasar', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_dasar', document.querySelector('#exampleFormControlSelect1').value);
        formData.append('area', document.querySelector('#exampleFormControlSelect2').value);
        formData.append('lokasi', document.querySelector('#exampleFormControlSelect3').value);

        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);

        // Ambil Superintendent
        const superintendentSelect = document.querySelector('#nikSuperintendent');
        const superintendentValue = superintendentSelect && superintendentSelect.value !== '' ? superintendentSelect.value : null;
        formData.append('nik_superintendent', superintendentValue);

    // Front loading data
    const unitSelects = document.querySelectorAll('#headerRow2 select.form-control');
   // console.log("Jumlah Unit Ditemukan:", unitSelects.length);

    const frontLoadingData = [];

    unitSelects.forEach((select, unitIndex) => {
        const nomorUnit = select.value;
        if (!nomorUnit) return;

        console.log(`Mengambil data untuk unit: ${nomorUnit}`);

        const siangData = [];
        const malamData = [];
        const checkedData = [];
        const keteranganData = [];
        const uuidData = [];

        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach((row, rowIndex) => {
            const jamSiang = row.querySelector('td:nth-child(1) input[type="hidden"]').value;
            const jamMalam = row.querySelector('td:nth-child(2) input[type="hidden"]').value;

            const unitColumnIndex = unitIndex + 3;
            const frontloadingUuidInput = row.querySelector(`td:nth-child(${unitColumnIndex}) input[name^="front_loading"][type="hidden"]`);

            if (frontloadingUuidInput) {
                uuidData.push(frontloadingUuidInput.value || generateUUID());
            } else {
                uuidData.push(generateUUID());
            }
            console.log(`Kolom Unit Index untuk Unit ${nomorUnit}:`, unitColumnIndex);

            const checkbox = row.querySelector(`td:nth-child(${unitColumnIndex}) input[type="checkbox"]`);
            const keteranganInput = row.querySelector(`td:nth-child(${unitColumnIndex}) input[type="text"]`);

            if (!checkbox || !keteranganInput) {
                console.warn(`Baris ${rowIndex} tidak memiliki input untuk unit ${nomorUnit}, dilewati.`);
                return;
            }

            siangData.push(jamSiang);
            malamData.push(jamMalam);
            checkedData.push(checkbox.checked);
            keteranganData.push(keteranganInput.value);
        });

        frontLoadingData.push({
            nomor_unit: nomorUnit,
            siang: siangData,
            malam: malamData,
            checked: checkedData,
            keterangan: keteranganData,
        });

        console.log(`Data untuk unit ${nomorUnit}:`, JSON.stringify(frontLoadingData[unitIndex], null, 2));
    });

   // console.log('FormData yang akan dikirim:', frontLoadingData);

    // Kirim data ke backend
    formData.append('front_loading', JSON.stringify(frontLoadingData));
    const alatSupportData = [];
const alatSupportAccordions = document.querySelectorAll('#accordionSupport .accordion-item');

alatSupportAccordions.forEach((accordion) => {
    const uuid = accordion.querySelector('input[name*="uuidSupport"]')?.value || null;
    const unit = accordion.querySelector('input[name*="unitSupport"]')?.value || null;
    let nama = accordion.querySelector('input[name*="namaSupport"]')?.value || null;
    let nik = null;

    if (nama && nama.includes('|')) {
        [nik, nama] = nama.split('|');
    }

    // Cek jika uuid adalah null, berarti data sudah dihapus, maka kita lewati data ini
    if (uuid === null) {
        return; // Skip jika uuid tidak ada (data dihapus)
    }

    const tanggal = accordion.querySelector('input[name*="tanggalSupport"]')?.value || null;
    const shift = accordion.querySelector('input[name*="shiftSupport"]')?.value || null;
    const hmAwal = accordion.querySelector('input[name*="hmAwalSupport"]')?.value || null;
    const hmAkhir = accordion.querySelector('input[name*="hmAkhirSupport"]')?.value || null;
    const total = accordion.querySelector('input[name*="totalSupport"]')?.value || null;
    const hmCash = accordion.querySelector('input[name*="hmCashSupport"]')?.value || null;
    const keterangan = accordion.querySelector('input[name*="keteranganSupport"]')?.value || null;

    // const formattedTanggal = tanggal ? new Date(tanggal).toISOString().split('T')[0] : null;

    alatSupportData.push({
        uuid: uuid,
        alat_unit: unit,
        nama_operator: nama,
        nik_operator: nik,
        tanggal_operator: tanggal,
        shift_operator: shift,
        hm_awal: hmAwal,
        hm_akhir: hmAkhir,
        total,
        hm_cash: hmCash,
        keterangan: keterangan,
    });
});

console.log('Data yang akan disimpan:', JSON.stringify(alatSupportData, null, 2));


formData.append('alat_support', JSON.stringify(alatSupportData));


        // Catatan Pengawas
      const catatanData = [];

    // // Ambil semua catatan dari accordion
    // const catatanAccordions = document.querySelectorAll('#accordionCatatan .accordion-item');
    // catatanAccordions.forEach((accordion, index) => {
    //     const start = accordion.querySelector(`input[name="catatan[${index}][start_catatan]"]`)?.value || null;
    //     const end = accordion.querySelector(`input[name="catatan[${index}][end_catatan]"]`)?.value || null;
    //     const description = accordion.querySelector(`input[name="catatan[${index}][description_catatan]"]`)?.value || null;

    //     catatanData.push({
    //         start_catatan: start,
    //         end_catatan: end,
    //         description_catatan: description,
    //     });
    // });

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


    // Debugging payload
  //  console.log(JSON.stringify(catatanData, null, 2));

    // Tambahkan catatan ke formData
    formData.append('catatan', JSON.stringify(catatanData));


        fetch('/save-draft', {
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
                            window.location.href = "{{ route('form-pengawas-new.show') }}";
                        }else{
                            location.reload();
                        }

                          // Halaman akan di-reload setelah popup Swal ditutup
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
        formData.append('tanggal_dasar', document.querySelector('#pc-datepicker-1').value);
        formData.append('shift_dasar', document.querySelector('#exampleFormControlSelect1').value);
        formData.append('area', document.querySelector('#exampleFormControlSelect2').value);
        formData.append('lokasi', document.querySelector('#exampleFormControlSelect3').value);

        // Supervisor
        const supervisorSelect = document.querySelector('#nikSupervisor');
        const supervisorValue = supervisorSelect && supervisorSelect.value !== '' ? supervisorSelect.value : null;
        formData.append('nik_supervisor', supervisorValue);

        // Superintendent
        const superintendentSelect = document.querySelector('#nikSuperintendent');
        const superintendentValue = superintendentSelect && superintendentSelect.value !== '' ? superintendentSelect.value : null;
        formData.append('nik_superintendent', superintendentValue);

        // Front loading data
        const unitSelects = document.querySelectorAll('#headerRow2 select.form-control');
        console.log("Jumlah Unit Ditemukan:", unitSelects.length);

        const frontLoadingData = [];

        unitSelects.forEach((select, unitIndex) => {
            const nomorUnit = select.value;
            if (!nomorUnit) return;

            console.log(`Mengambil data untuk unit: ${nomorUnit}`);

            const siangData = [];
            const malamData = [];
            const checkedData = [];
            const keteranganData = [];
            const uuidData = [];

            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach((row, rowIndex) => {
                const jamSiang = row.querySelector('td:nth-child(1) input[type="hidden"]').value;
                const jamMalam = row.querySelector('td:nth-child(2) input[type="hidden"]').value;

                const unitColumnIndex = unitIndex + 3;
                const frontloadingUuidInput = row.querySelector(`td:nth-child(${unitColumnIndex}) input[name^="front_loading"][type="hidden"]`);

                if (frontloadingUuidInput) {
                    uuidData.push(frontloadingUuidInput.value || generateUUID());
                } else {
                    uuidData.push(generateUUID());
                }
                console.log(`Kolom Unit Index untuk Unit ${nomorUnit}:`, unitColumnIndex);

                const checkbox = row.querySelector(`td:nth-child(${unitColumnIndex}) input[type="checkbox"]`);
                const keteranganInput = row.querySelector(`td:nth-child(${unitColumnIndex}) input[type="text"]`);

                if (!checkbox || !keteranganInput) {
                    console.warn(`Baris ${rowIndex} tidak memiliki input untuk unit ${nomorUnit}, dilewati.`);
                    return;
                }

                siangData.push(jamSiang);
                malamData.push(jamMalam);
                checkedData.push(checkbox.checked);
                keteranganData.push(keteranganInput.value);
            });

            frontLoadingData.push({
                nomor_unit: nomorUnit,
                siang: siangData,
                malam: malamData,
                checked: checkedData,
                keterangan: keteranganData,
            });

            console.log(`Data untuk unit ${nomorUnit}:`, JSON.stringify(frontLoadingData[unitIndex], null, 2));
        });

        console.log('FormData yang akan dikirim:', frontLoadingData);

        // Kirim data ke backend
        formData.append('front_loading', JSON.stringify(frontLoadingData));



        // Alat Support
          const alatSupportData = [];
        const alatSupportAccordions = document.querySelectorAll('#accordionSupport .accordion-item');

        //console.log(alatSupportAccordions);

        alatSupportAccordions.forEach((accordion, index) => {

            const unit = accordion.querySelector('input[name*="unitSupport"]')?.value || null;
            let nama = accordion.querySelector('input[name*="namaSupport"]')?.value || null;
            let nik = null;

            if (nama && nama.includes('|')) {
                [nik, nama] = nama.split('|');
            }


            const tanggal = accordion.querySelector('input[name*="tanggalSupport"]')?.value || null;
            const shift = accordion.querySelector('input[name*="shiftSupport"]')?.value || null;
            const hmAwal = accordion.querySelector('input[name*="hmAwalSupport"]')?.value || null;
            const hmAkhir = accordion.querySelector('input[name*="hmAkhirSupport"]')?.value || null;
            const total = accordion.querySelector('input[name*="totalSupport"]')?.value || null;
            const hmCash = accordion.querySelector('input[name*="hmCashSupport"]')?.value || null;
            const keterangan = accordion.querySelector('input[name*="keteranganSupport"]')?.value || null;

            const formattedTanggal = new Date(tanggal).toISOString().split('T')[0];


            alatSupportData.push({
                alat_unit: unit,
                nama_operator: nama,
                nik_operator: nik,
                tanggal_operator: tanggal,
                shift_operator: shift,
                hm_awal: hmAwal,
                hm_akhir: hmAkhir,
                total,
                hm_cash: hmCash,
                keterangan: keterangan,
            });
        });

        // console.log(JSON.stringify(alatSupportData, null, 2)); // Debugging

        formData.append('alat_support', JSON.stringify(alatSupportData));



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
        formData.append('catatan', JSON.stringify(catatanData));

        fetch('/form-pengawas-new/post', {
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


<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectShift = document.getElementById("exampleFormControlSelect1");
    if (selectShift) {
        handleChangeShift(selectShift.value); // Panggil fungsi saat halaman dimuat
    }
});

    function handleChangeShift(value) {
    const thJam = document.getElementById('thJam');
    const thSiang = document.getElementById('thSiang');
    const thMalam = document.getElementById('thMalam');
    const tableRows = document.querySelectorAll('#tableBody tr');

    if (value == 1) value = "Siang";
    if (value == 2) value = "Malam";


    // Atur colspan di header
    if (value === "Siang") {
    thJam.colSpan = 1;
    thSiang.style.display = "table-cell";
    thMalam.style.display = "none";
    } else if (value === "Malam") {
    thJam.colSpan = 1;
    thSiang.style.display = "none";
    thMalam.style.display = "table-cell";
    } else {
    thJam.colSpan = 2;
    thSiang.style.display = "table-cell";
    thMalam.style.display = "table-cell";
    }

    // Sembunyikan hanya <td> di tbody berdasarkan shift
        tableRows.forEach(row => {
        const tdSiang = row.querySelector('td:nth-child(1)');
        const tdMalam = row.querySelector('td:nth-child(2)');

        if (value === "Siang") {
        if (tdSiang) tdSiang.style.display = "table-cell";
        if (tdMalam) tdMalam.style.display = "none";
        } else if (value === "Malam") {
        if (tdSiang) tdSiang.style.display = "none";
        if (tdMalam) tdMalam.style.display = "table-cell";
        } else {
        if (tdSiang) tdSiang.style.display = "table-cell";
        if (tdMalam) tdMalam.style.display = "table-cell";
        }
        });
        }
</script>

{{-- Script Cari User --}}
<script>
    function cariUser(nikInputId, namaInputId, role) {
        const nik = document.getElementById(nikInputId).value;

        if (!nik) {
            Swal.fire('Error', `NIK ${role} tidak boleh kosong!`, 'error');
            return;
        }

        const url = "{{ route('cariUsers') }}?nik=" + nik;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Menampilkan nama user
                    Swal.fire('Success', `User ditemukan! [${data.name}]`, 'success');
                    document.getElementById(namaInputId).value = data.name;
                } else {
                    Swal.fire('Not Found', `User ${role} tidak ditemukan!`, 'warning');
                    document.getElementById(namaInputId).value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat mencari data!', 'error');
            });
    }

    // Event listener untuk tombol cari Supervisor
    document.getElementById('btnCariSupervisor').addEventListener('click', function () {
        cariUser('nikSupervisor', 'namaSupervisor', 'Supervisor');
    });

    // Event listener untuk tombol cari Superintendent
    document.getElementById('btnCariSuperintendent').addEventListener('click', function () {
        cariUser('nikSuperintendent', 'namaSuperintendent', 'Superintendent');
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

    const exa = @json($data['EX']);

    addColumnBtn.addEventListener('click', () => {
        unitCount++;

        const newHeader1 = document.createElement('th');
        newHeader1.classList.add('unitHeader');
        newHeader1.textContent = `Nomor Unit`;
        headerRow1.appendChild(newHeader1);

        const newHeader2 = document.createElement('th');
        const selectElement = document.createElement('select');
        selectElement.name = `front_loading[${unitCount}][nomor_unit]`;
        selectElement.id = `frontUnitNumber_${unitCount}`;
        selectElement.classList.add('form-control', 'unit-select');

        const emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.textContent = '';
        selectElement.appendChild(emptyOption);

        exa.forEach(option => {
            if (option.VHC_ID) {
                const optionElement = document.createElement('option');
                optionElement.value = option.VHC_ID;
                optionElement.textContent = option.VHC_ID;
                selectElement.appendChild(optionElement);
            }
        });

        newHeader2.appendChild(selectElement);
        headerRow2.appendChild(newHeader2);
        var values = [
            "07.00 - 08.00 | 19.00 - 20.00",
            "08.00 - 09.00 | 20.00 - 21.00",
            "09.00 - 10.00 | 21.00 - 22.00",
            "10.00 - 11.00 | 22.00 - 23.00",
            "11.00 - 12.00 | 23.00 - 24.00",
            "12.00 - 13.00 | 24.00 - 01.00",
            "13.00 - 14.00 | 01.00 - 02.00",
            "14.00 - 15.00 | 02.00 - 03.00",
            "15.00 - 16.00 | 03.00 - 04.00",
            "16.00 - 17.00 | 04.00 - 05.00",
            "17.00 - 18.00 | 05.00 - 06.00",
            "18.00 - 19.00 | 06.00 - 07.00",
        ];
        var index = 0;
        for (const row of tableBody.rows) {
        const newCell = document.createElement('td');
        const uuid = generateUUID(); // Generate UUID here

        newCell.innerHTML = `
            <input type="hidden" name="front_loading[${unitCount}][time][${index}][front_loading_uuid]" value="${uuid}">
            <div class="d-flex align-items-center gap-2">
                <input type="checkbox" value="true" name="front_loading[${unitCount}][time][${index}][checked]" class="form-check-input">
                <input type="text" name="front_loading[${unitCount}][time][${index}][keterangan]" placeholder="Keterangan" class="form-control">
            </div>`;
        row.appendChild(newCell);
        index++;
    }
    });

    function removeColumnBtn(frontUUID) {

        console.log('Menghapus front loading dengan UUID:', frontUUID);
        if(frontUUID){
                Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus Nomor Unit ${unitCount}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Menghapus data support dengan ID:', frontUUID);
                        // Jika frontUUID ada, kirim permintaan ke server
                        fetch(`/delete-front-loading/${frontUUID}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                            .then((response) => {
                                Swal.fire(
                                        'Dihapus!',
                                        'Data berhasil dihapus.',
                                        'success'
                                        ).then(() => {
                                        location.reload();  // Halaman akan di-reload setelah popup Swal ditutup
                                    });
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            });
                }
            });
        }else{
            Swal.fire(
                'Gagal!',
                'Maaf, Data Front Loading harus disimpan terlebih dahulu...',
                'info'
            );
         }


    }

    // removeColumnBtn.addEventListener('click', () => {
    //     if (unitCount > 1) {
    //         Swal.fire({
    //             title: 'Apakah Anda yakin?',
    //             text: `Anda akan menghapus Nomor Unit ${unitCount}.`,
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#3085d6',
    //             cancelButtonColor: '#d33',
    //             confirmButtonText: 'Ya, Hapus!',
    //             cancelButtonText: 'Batal'
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 headerRow1.lastElementChild.remove();
    //                 headerRow2.lastElementChild.remove();
    //                 for (const row of tableBody.rows) {
    //                     row.lastElementChild.remove();
    //                 }
    //                 unitCount--;
    //                 Swal.fire('Dihapus!', `Nomor Unit ${unitCount + 1} telah dihapus.`, 'success');
    //             }
    //         });
    //     } else {
    //         Swal.fire('Tidak Bisa Dihapus', 'Kolom Nomor Unit 1 tidak boleh dihapus.', 'error');
    //     }
    // });

    function validateCheckboxes() {
        let isChecked = false;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach((row) => {
            const checkboxesInRow = row.querySelectorAll('input[type="checkbox"]');
            checkboxesInRow.forEach((checkbox) => {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });
        });

        return isChecked;
    }

</script>

{{-- Script Form Alat Support --}}
<script>
    let supportCount = 0;

    document.addEventListener("DOMContentLoaded", function () {
        const alatSupports = @json($alatSupports);
        console.log(alatSupports);
         // Data dari backend

        const accordionContainer = document.getElementById('accordionSupport');

        // Render ulang data alatSupports dari backend
        alatSupports.forEach((support, index) => {

            const accordionId = `support${index + supportCount}`;
            const collapseId = `collapseSupport${index + supportCount}`;
            const namaOperator = `${support.nik_operator}|${support.nama_operator}`;

            const shiftText = document.querySelector(`#shiftSupport option[value="${support.shift_operator_id}"]`)?.text.trim() || '';

            const accordionItem = `
            <div class="accordion-item" id="${accordionId}" data-support-id="${support.id}">
                    <h2 class="accordion-header" id="heading${accordionId}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                            #${support.alat_unit}
                        </button>
                    </h2>
                    <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionSupport">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>UUID</th>
                                            <td><input type="hidden" name="alat_support[${index}][uuidSupport]" value="${support.uuid}">${support.uuid}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit</th>
                                            <td><input type="hidden" name="alat_support[${index}][unitSupport]" value="${support.alat_unit}">${support.alat_unit}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                        <td><input type="hidden" name="alat_support[${index}][namaSupport]" value="${namaOperator}">${namaOperator}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td><input type="hidden" name="alat_support[${index}][tanggalSupport]" value="${support.tanggal_operator}">${support.tanggal_operator}</td>
                                        </tr>
                                        <tr>
                                            <th>Shift</th>
                                            <td><input type="hidden" name="alat_support[${index}][shiftSupport]" value="${support.shift_operator_id}">${shiftText}</td>
                                        </tr>
                                        <tr>
                                            <th>HM Awal</th>
                                            <td><input type="hidden" name="alat_support[${index}][hmAwalSupport]" value="${support.hm_awal}">${support.hm_awal}</td>
                                        </tr>
                                        <tr>
                                            <th>HM Akhir</th>
                                            <td><input type="hidden" name="alat_support[${index}][hmAkhirSupport]" value="${support.hm_akhir}">${support.hm_akhir}</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td><input type="hidden" name="alat_support[${index}][totalSupport]" value="${support.hm_total}">${support.hm_total}</td>
                                        </tr>
                                        <tr>
                                            <th>HM Cash</th>
                                            <td><input type="hidden" name="alat_support[${index}][hmCashSupport]" value="${support.hm_cash}">${support.hm_cash}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td><input type="hidden" name="alat_support[${index}][keteranganSupport]" value="${support.keterangan}">${support.keterangan}</td>
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

    // Menghitung Total otomatis berdasarkan HM Akhir - HM Awal
    document.getElementById('hmAwalSupport').addEventListener('input', calculateTotal);
    document.getElementById('hmAkhirSupport').addEventListener('input', calculateTotal);


    function calculateTotal() {
        const hmAwal = parseFloat(document.getElementById('hmAwalSupport').value) || 0;
        const hmAkhir = parseFloat(document.getElementById('hmAkhirSupport').value) || 0;

        // console.log(hmAwal, hmAkhir);


        const total = hmAkhir - hmAwal;
        // console.log(total);
        document.getElementById('totalSupport').value = (total >= 0 ? total : 0).toFixed(2);
    }

    document.getElementById('saveSupport').addEventListener('click', () => {
    //    const jenis = document.getElementById('jenisSupport').value || '';
        const unit = document.getElementById('unitSupport').value || '';
        // const nik = document.getElementById('nikSupport').value || '';
        const nama = document.getElementById('namaSupport').value || '';
        const tanggal = document.getElementById('tanggalSupport').value || '';
        const shift = document.getElementById('shiftSupport').value || '';
        const textShift = document.getElementById('shiftSupport').selectedOptions[0]?.text.trim() || '';
        const hmAwal = document.getElementById('hmAwalSupport').value || '';
        const hmAkhir = document.getElementById('hmAkhirSupport').value || '';
        const hmCash = document.getElementById('hmCashSupport').value || '';
        const total = document.getElementById('totalSupport').value || '';
        const keterangan = document.getElementById('keteranganSupport').value || '';

        if ( !nama || !tanggal || !shift || !hmAwal || !hmAkhir || !hmCash) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Semua field harus diisi!'
            });
            return;
        }

        supportCount++;

        const accordionId = `support${supportCount}`;
        const collapseId = `collapseSupport${supportCount}`;

        const newAccordionItem = `<div class="accordion-item" id="${accordionId}">
                                        <h2 class="accordion-header" id="heading${accordionId}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                                #${unit}
                                            </button>
                                        </h2>
                                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionSupport">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th>Unit</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][uuidSupport]" value="${generateUUID()}">${generateUUID()}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Unit</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][unitSupport]" value="${unit}">${unit}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][namaSupport]" value="${nama}">${nama}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][tanggalSupport]" value="${tanggal}">${tanggal}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Shift</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][shiftSupport]" value="${shift}">${textShift}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>HM Awal</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][hmAwalSupport]" value="${hmAwal}">${hmAwal}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>HM Akhir</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][hmAkhirSupport]" value="${hmAkhir}">${hmAkhir}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][totalSupport]" value="${total}">${total}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>HM Cash</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][hmCashSupport]" value="${hmCash}">${hmCash}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Keterangan</th>
                                                                <td><input type="hidden" name="alat_support[${supportCount-1}][keteranganSupport]" value="${keterangan}">${keterangan}</td>
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
            //  document.getElementById('jenisSupport').value = null;
            //  document.getElementById('unitSupport').value = null;
            // //  document.getElementById('nikSupport').value = null;
            //  document.getElementById('namaSupport').value = null;
             const today = new Date();

            // Format tanggal menjadi YYYY-MM-DD
            const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
            '0')}/${today.getFullYear()}`;
             document.getElementById('tanggalSupport').value = formattedDate;
             document.getElementById('shiftSupport').value = null;
             document.getElementById('hmAwalSupport').value = null;
             document.getElementById('hmAkhirSupport').value = null;
             document.getElementById('hmCashSupport').value = null;
             document.getElementById('totalSupport').value = null;
             document.getElementById('keteranganSupport').value = null;
        });
        // document.getElementById("formSupport").reset();

        // // Reset form setelah data ditambahkan
    });

    // Fungsi untuk menghapus item support
    function removeSupport(accordionId) {
    const alatSupports = @json($alatSupports); // Data dari backend

    console.log(alatSupports);
    console.log(alatSupports.length);

    const item = document.getElementById(accordionId);
    const supportId = item ? item.getAttribute('data-support-id') : null;

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
                fetch(`/delete-support/${supportId}`, {
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
                                location.reload(); // Reload halaman hanya jika supportId ada
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
                );
                // Jangan reload halaman jika supportId == null
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
                                    <td><input type="hidden" name="catatan[${index}][start_catatan]" value="${catatan.jam_start || ''}">${catatan.jam_start || ''}</td>
                                </tr>
                                <tr>
                                    <th>End</th>
                                    <td><input type="hidden" name="catatan[${index}][end_catatan]" value="${catatan.jam_stop || ''}">${catatan.jam_stop || ''}</td>
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
                fetch(`/delete/catatan-pengawas/${catatanId}`, {
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
                                location.reload(); // Reload hanya jika catatanId ada
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
                );
                // Jangan reload halaman jika catatanId == null
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
        const select2 = document.getElementById("exampleFormControlSelect2");
        const select3 = document.getElementById("exampleFormControlSelect3");
        const select4 = document.getElementById("nikSupervisor");
        const select5 = document.getElementById("nikSuperintendent");


        if (!date.value || !select1.value || !select2.value || !select3.value || !select4.value || !select5.value) {
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

        if(select3.value == 3){
            if(frontcheckboxes.length < 1){
                Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nomor Unit harus diisi pada form Front Loading',
                confirmButtonText: 'OK'
                });
            return false;

            if(!isChecked){
                Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap centang minimal 1 kotak pada form Front Loading',
                confirmButtonText: 'OK'
                });
            return false

            }
        }
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
