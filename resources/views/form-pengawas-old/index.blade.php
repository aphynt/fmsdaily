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
                    <h3>Laporan Harian Foreman</h3>
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
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Info!</strong>
                        Anda sudah mengisi Laporan Harian hari ini
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('form-pengawas-old.post') }}" method="post"
                                onsubmit="validateForm(event)" id="submitFormKerja">
                                @csrf
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
                                                                name="tanggal_dasar">
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
                                                                    <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
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
                                                                    <option value="{{ $ar->id }}">{{ $ar->keterangan }}</option>
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
                                                                    <option value="{{ $lok->id }}">{{ $lok->keterangan }}</option>
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
                                                                <option value="{{ $sv->NRP }}|{{ $sv->PERSONALNAME }}">
                                                                    {{ $sv->NRP }}|{{ $sv->PERSONALNAME }}</option>
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
                                                                <option value="{{ $st->NRP }}|{{ $st->PERSONALNAME }}">
                                                                    {{ $st->NRP }}|{{ $st->PERSONALNAME }} ({{ $st->JABATAN }})</option>
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
                                                <button type="button" id="addColumnBtn"
                                                    class="btn btn-primary mb-3">Tambah Kolom</button>
                                                <button type="button" id="removeColumnBtn"
                                                    class="btn btn-danger mb-3">Hapus Kolom</button>
                                                <div class="table-responsive">
                                                    <table id="dynamicTable" class="table table-bordered">
                                                        <thead style="text-align: center; vertical-align: middle;">
                                                            <tr id="headerRow1">
                                                                <th colspan="2" id="thJam">Jam</th>
                                                                <th class="unitHeader" scope="col">Nomor Unit 1</th>
                                                            </tr>
                                                            <tr id="headerRow2">
                                                                <th id="thSiang">Siang</th>
                                                                <th id="thMalam">Malam</th>
                                                                <th>
                                                                    <select name="front_loading[0][nomor_unit]"
                                                                        id="frontUnitNumber" class="form-control">
                                                                        <option value="" disabled selected>Pilih
                                                                        </option>
                                                                        @foreach ($data['EX'] as $exa)
                                                                        <option value="{{ $exa->VHC_ID }}">
                                                                            {{ $exa->VHC_ID }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tableBody">
                                                            <tr>
                                                                <td><input type="hidden" value="07.00 - 08.00">07.00 -
                                                                    08.00</td>
                                                                <td><input type="hidden" value="19.00 - 20.00">19.00 -
                                                                    20.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][0][checked]">
                                                                        <input type="hidden"
                                                                            value="07.00 - 08.00 | 19.00 - 20.00"
                                                                            name="front_loading[0][time][0][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][0][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][0][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="08.00 - 09.00">08.00 -
                                                                    09.00</td>
                                                                <td><input type="hidden" value="20.00 - 21.00">20.00 -
                                                                    21.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][1][checked]">
                                                                        <input type="hidden"
                                                                            value="08.00 - 09.00 | 20.00 - 21.00"
                                                                            name="front_loading[0][time][1][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][1][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][1][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="09.00 - 10.00">09.00 -
                                                                    10.00</td>
                                                                <td><input type="hidden" value="21.00 - 22.00">21.00 -
                                                                    22.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][2][checked]">
                                                                        <input type="hidden"
                                                                            value="09.00 - 10.00 | 21.00 - 22.00"
                                                                            name="front_loading[0][time][2][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][2][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][2][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="10.00 - 11.00">10.00 -
                                                                    11.00</td>
                                                                <td><input type="hidden" value="22.00 - 23.00">22.00 -
                                                                    23.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][3][checked]">
                                                                        <input type="hidden"
                                                                            value="10.00 - 11.00 | 22.00 - 23.00"
                                                                            name="front_loading[0][time][3][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][3][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][3][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="11.00 - 12.00">11.00 -
                                                                    12.00</td>
                                                                <td><input type="hidden" value="23.00 - 24.00">23.00 -
                                                                    24.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][4][checked]">
                                                                        <input type="hidden"
                                                                            value="11.00 - 12.00 | 23.00 - 24.00"
                                                                            name="front_loading[0][time][4][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][4][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][4][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="12.00 - 13.00">12.00 -
                                                                    13.00</td>
                                                                <td><input type="hidden" value="24.00 - 01.00">24.00 -
                                                                    01.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][5][checked]">
                                                                        <input type="hidden"
                                                                            value="12.00 - 13.00 | 24.00 - 01.00"
                                                                            name="front_loading[0][time][5][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][5][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][5][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="13.00 - 14.00">13.00 -
                                                                    14.00</td>
                                                                <td><input type="hidden" value="01.00 - 02.00">01.00 -
                                                                    02.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][6][checked]">
                                                                        <input type="hidden"
                                                                            value="13.00 - 14.00 | 01.00 - 02.00"
                                                                            name="front_loading[0][time][6][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][6][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][6][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="14.00 - 15.00">14.00 -
                                                                    15.00</td>
                                                                <td><input type="hidden" value="02.00 - 03.00">02.00 -
                                                                    03.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][7][checked]">
                                                                        <input type="hidden"
                                                                            value="14.00 - 15.00 | 02.00 - 03.00"
                                                                            name="front_loading[0][time][7][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][7][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][7][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="15.00 - 16.00">15.00 -
                                                                    16.00</td>
                                                                <td><input type="hidden" value="03.00 - 04.00">03.00 -
                                                                    04.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][8][checked]">
                                                                        <input type="hidden"
                                                                            value="15.00 - 16.00 | 03.00 - 04.00"
                                                                            name="front_loading[0][time][8][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][8][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][8][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="16.00 - 17.00">16.00 -
                                                                    17.00</td>
                                                                <td><input type="hidden" value="04.00 - 05.00">04.00 -
                                                                    05.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][9][checked]">
                                                                        <input type="hidden"
                                                                            value="16.00 - 17.00 | 04.00 - 05.00"
                                                                            name="front_loading[0][time][9][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][9][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][9][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="17.00 - 18.00">17.00 -
                                                                    18.00</td>
                                                                <td><input type="hidden" value="05.00 - 06.00">05.00 -
                                                                    06.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][10][checked]">
                                                                        <input type="hidden"
                                                                            value="17.00 - 18.00 | 05.00 - 06.00"
                                                                            name="front_loading[0][time][10][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][10][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][10][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                        </ </div>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><input type="hidden" value="18.00 - 19.00">18.00 -
                                                                    19.00</td>
                                                                <td><input type="hidden" value="06.00 - 07.00">06.00 -
                                                                    07.00</td>
                                                                <td>
                                                                    <div
                                                                        class="grid gap-3 d-flex align-items-center justify-content-center">
                                                                        <input type="hidden" value="false"
                                                                            name="front_loading[0][time][11][checked]">
                                                                        <input type="hidden"
                                                                            value="18.00 - 19.00 | 06.00 - 07.00"
                                                                            name="front_loading[0][time][11][value]">
                                                                        <input type="checkbox" value="true"
                                                                            name="front_loading[0][time][11][checked]"
                                                                            class="form-check-input">
                                                                        <input type="text"
                                                                            name="front_loading[0][time][11][keterangan]"
                                                                            placeholder="Keterangan"
                                                                            class="form-control">
                                                                        </ </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end job detail tab pane -->
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
                                                @include('form-pengawas-old.modal.alat-support')
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
                                                @include('form-pengawas-old.modal.catatan-pengawas')
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
                                                                class="form-check-input" id="customCheck1"> <label
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
                                            <div class="previous me-2">
                                                <a href="javascript:void(0);"><span class="badge bg-secondary" style="font-size:14px"><i class="fa-solid fa-arrow-left"></i> Kembali</span></a>
                                                {{-- <a href="javascript:void(0);" class="btn btn-secondary btn-md">
                                                    <i class="fa-solid fa-arrow-left"></i> Kembali
                                                </a> --}}
                                            </div>
                                            <div class="next me-3">
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
</div>

@include('layout.footer')

<script>
    // Ambil elemen form dan tombol submit
    const formKerja = document.getElementById('submitFormKerja');
    const submitButtonKerja = document.getElementById('submitButtonKerja');

    formKerja.addEventListener('submit', function() {
        event.preventDefault();
        submitButtonKerja.disabled = true;
        submitButtonKerja.innerText = 'Processing...';

    });
</script>

<script>
    function handleChangeShift(value) {
    const thJam = document.getElementById('thJam');
    const thSiang = document.getElementById('thSiang');
    const thMalam = document.getElementById('thMalam');
    const tableRows = document.querySelectorAll('#tableBody tr');

    if(value == 1) value = "Siang";
    if(value == 2) value = "Malam";
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
    const addColumnBtn = document.getElementById('addColumnBtn');
    const removeColumnBtn = document.getElementById('removeColumnBtn');
    const headerRow1 = document.getElementById('headerRow1');
    const headerRow2 = document.getElementById('headerRow2');
    const tableBody = document.getElementById('tableBody');

    let unitCount = 1;

    const exa = @json($data['EX']);

    addColumnBtn.addEventListener('click', () => {
        unitCount++;

        const newHeader1 = document.createElement('th');
        newHeader1.classList.add('unitHeader');
        newHeader1.textContent = `Nomor Unit ${unitCount}`;
        headerRow1.appendChild(newHeader1);

        const newHeader2 = document.createElement('th');
        const selectElement = document.createElement('select');
        selectElement.name = `front_loading[${unitCount}][nomor_unit]`;
        selectElement.classList.add('form-control');

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
            newCell.innerHTML =
           `<div class="grid gap-3 d-flex align-items-center justify-content-center">
                <input type="hidden" value="false" name="front_loading[${unitCount}][time][${index}][checked]">
                <input type="hidden" value="${values[index]}" name="front_loading[${unitCount}][time][${index}][value]">
                <input type="checkbox" value="true" name="front_loading[${unitCount}][time][${index}][checked]" class="form-check-input">
                <input type="text" name="front_loading[${unitCount}][time][${index}][keterangan]" placeholder="Keterangan" class="form-control">
            </div>`;

            row.appendChild(newCell);
            index++;
        }
    });

    removeColumnBtn.addEventListener('click', () => {
        if (unitCount > 1) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus Nomor Unit ${unitCount}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    headerRow1.lastElementChild.remove();
                    headerRow2.lastElementChild.remove();
                    for (const row of tableBody.rows) {
                        row.lastElementChild.remove();
                    }
                    unitCount--;
                    Swal.fire('Dihapus!', `Nomor Unit ${unitCount + 1} telah dihapus.`, 'success');
                }
            });
        } else {
            Swal.fire('Tidak Bisa Dihapus', 'Kolom Nomor Unit 1 tidak boleh dihapus.', 'error');
        }
    });

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

    // Menghitung Total otomatis berdasarkan HM Akhir - HM Awal
    document.getElementById('hmAwalSupport').addEventListener('input', calculateTotal);
    document.getElementById('hmAkhirSupport').addEventListener('input', calculateTotal);

    function calculateTotal() {
        const hmAwal = parseFloat(document.getElementById('hmAwalSupport').value) || 0;
        const hmAkhir = parseFloat(document.getElementById('hmAkhirSupport').value) || 0;
        const total = hmAkhir - hmAwal;
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

        if ( !nama || !tanggal || !shift || !hmAwal || !hmAkhir) {
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
                                                #${supportCount}. ${unit}
                                            </button>
                                        </h2>
                                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="heading${accordionId}" data-bs-parent="#accordionSupport">
                                            <div class="accordion-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>

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
            text: 'Data support ditambahkan!',
            timer: 2000,
            showConfirmButton: false
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
        const item = document.getElementById(accordionId);
        if (item) {
            item.remove();
            Swal.fire({
                icon: 'info',
                title: 'Baris Dihapus',
                text: 'Baris dihapus!',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }
</script>

{{-- Script Form Catatan Pengawas --}}
<script>
    let catatanCount = 0;

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
            text: 'Catatan ditambahkan!',
            timer: 2000,
            showConfirmButton: false
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
        const item = document.getElementById(accordionId);
        if (item) {
            item.remove();
            Swal.fire({
                icon: 'info',
                title: 'Catatan Dihapus',
                text: 'Catatan berhasil dihapus!',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }
</script>


{{-- Script Finishing --}}
<script>
    function validateForm(event) {
        event.preventDefault();
        const date = document.getElementById("pc-datepicker-1");
        const select1 = document.getElementById("exampleFormControlSelect1");
        const select2 = document.getElementById("exampleFormControlSelect2");
        const select3 = document.getElementById("exampleFormControlSelect3");
        const select4 = document.getElementById("nikSupervisor");
        const select5 = document.getElementById("nikSuperintendent");
        const formKerja = document.getElementById('submitFormKerja');

        console.log(select3.value == 3);



        if (!date.value || !select1.value || !select2.value || !select3.value || !select4.value || !select5.value) {

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "Kolom Tanggal, Shift, Area, Unit Kerja, Supervisor dan Superintendent harus diisi",
                confirmButtonText: 'OK'
            }).then((result) => {
                // Setelah pengguna menekan OK pada Swal, ubah tombol submit kembali
                submitButtonKerja.disabled = false;
                submitButtonKerja.innerText = 'Submit';
            });

            return;
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
        if(select3.value == 3){
            if(!frontN.value){

                Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Nomor Unit harus diisi pada form Front Loading',
                confirmButtonText: 'OK'
                }).then((result) => {
                // Setelah pengguna menekan OK pada Swal, ubah tombol submit kembali
                submitButtonKerja.disabled = false;
                submitButtonKerja.innerText = 'Submit';
            });
                return;
            }
            if(!isChecked){

                Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap centang minimal 1 kotak pada form Front Loading',
                confirmButtonText: 'OK'
                }).then((result) => {
                // Setelah pengguna menekan OK pada Swal, ubah tombol submit kembali
                submitButtonKerja.disabled = false;
                submitButtonKerja.innerText = 'Submit';
            });
                return;
            }
        }

        var checkBox = document.getElementById("customCheck1");
        if (!checkBox.checked) {

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Harap centang kotak untuk menyatakan bahwa Anda sudah mengisi form ini dengan benar.',
                confirmButtonText: 'OK'
            }).then((result) => {
                submitButtonKerja.disabled = false;
                submitButtonKerja.innerText = 'Submit';
            });
            return;
        }
            formKerja.submit();

        // return true;
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
