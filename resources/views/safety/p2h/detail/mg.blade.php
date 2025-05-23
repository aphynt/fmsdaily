@include('layout.head', ['title' => 'Form P2H Unit'])
@include('layout.sidebar')
@include('layout.header')
@php
use Carbon\Carbon;
$detailFiltered = $detail->filter(function($item) {
        return $item->CHECKLISTVAL == 0;
    });

@endphp

@if ($jumlahAATerisi >= 1 && !$verifikasiMekanik)
<div id="notifier" class="notifier-container">
    <span id="notification-message"></span>
</div>

<script>
    function showNotification(message, bgColor = '#f44336') {
        const notifier = document.getElementById("notifier");
        const notificationMessage = document.getElementById("notification-message");
        notificationMessage.innerText = message;
        notifier.style.backgroundColor = bgColor;
        notifier.classList.add("show");
        setTimeout(() => {
            notifier.classList.add("hide");
            setTimeout(() => {
                notifier.classList.remove("show", "hide");
            }, 500);
        }, 7000);
    }

    document.addEventListener("DOMContentLoaded", function () {
        showNotification("Temuan kode AA harus diverifikasi oleh Pengawas Mekanik terlebih dahulu.");
    });
</script>

@endif
<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-2"><img
                                                src="{{ asset('dashboard/assets') }}/images/logo-full.png"
                                                class="img-fluid" alt="images" width="200px">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <h6>FM-SHE-32/06/08/05/25</h6>
                                    </div>
                                </div>
                            </div>
                            <h3 style="text-align: center;">PEMERIKSAAN DAN PERAWATAN HARIAN (P2H)</h3>
                            <form action="{{ route('p2h.detail.post') }}" method="post">
                                @csrf
                                <input type="hidden" name="VHC_ID" value="{{ $detail->first()->VHC_ID }}">
                                <input type="hidden" name="OPR_SHIFTNO" value="{{ $detail->first()->OPR_SHIFTNO }}">
                                <input type="hidden" name="OPR_REPORTTIME" value="{{ $detail->first()->OPR_REPORTTIME }}">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-0">Unit:</h6>
                                            <h5>{{ $detail->first()->VHC_ID }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-0">Shift:</h6>
                                            <h5>{{ $detail->first()->OPR_SHIFTNO == 6 ? 'Siang' : 'Malam' }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-0">Hari/ Tanggal:</h6>
                                            <h5>{{ Carbon::parse($detail->first()->OPR_REPORTTIME)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-0">Jam:</h6>
                                            <h5>{{ Carbon::parse($detail->first()->OPR_REPORTTIME)->locale('id')->isoFormat('HH:mm') }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                @if ($jumlahAATerisi >= 1 && !$verifikasiMekanik && !in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))
                                    <div class="alert alert-danger" role="alert">Terdapat temuan kode AA. Verifikasi harus dilakukan oleh Pengawas Mekanik terlebih dahulu.</div>
                                @endif
                                {{-- @dd($verifikasiMekanik) --}}
                                @if ($verifikasiMekanik)
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Group</th>
                                                        <th rowspan="2">Item</th>
                                                        <th rowspan="2">Value</th>
                                                        <th rowspan="2">CATATAN OPERATOR</th>
                                                        <th rowspan="2">Catatan MEKANIK</th>
                                                        <th colspan="2">Komentar</th>
                                                    </tr>
                                                     <tr>
                                                        <th>KBJ</th>
                                                        <th>Jawaban</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($detailP2H as $dp)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><input type="hidden" name="CHECKLISTGROUPID[]" value="{{ $dp->CHECKLISTGROUPID }}">{{ $dp->CHECKLISTGROUPID }}</td>
                                                        <td><input type="hidden" name="CHECKLISTITEMDESCRIPTION[]" value="{{ $dp->CHECKLISTITEMDESCRIPTION }}"> {{ $dp->CHECKLISTITEMDESCRIPTION }}</td>
                                                        <td style="text-align: center">
                                                            <input type="hidden" name="CHECKLISTVAL[]" value="{{ $dp->CHECKLISTVAL }}">
                                                            @if ( $dp->CHECKLISTVAL == 0)
                                                            ❌
                                                            @elseif ( $dp->CHECKLISTVAL == 1)
                                                            ✔️
                                                            @else
                                                            -
                                                            {{-- 🔘 --}}
                                                            @endif
                                                        </td>
                                                        <td><input type="hidden" name="CHECKLISTNOTES[]" value="{{ $dp->CHECKLISTNOTES }}">{{ $dp->CHECKLISTNOTES }}</td>
                                                        <td><input type="hidden" name="CATATAN_MEKANIK[]" value="{{ $dp->CATATAN_MEKANIK }}">{{ $dp->CATATAN_MEKANIK }}</td>
                                                        <td><input type="text" class="form-control" style="min-width: 50px;" name="KBJ[]"></td>
                                                        <td><input type="text" class="form-control" style="min-width: 250px" name="JAWABAN[]"></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-start">
                                            <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                        </div>
                                        <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" style="width: 100%; font-size:14pt; padding-left: 20px; padding-right: 20px;">
                                                    Verifikasi
                                                </button>
                                            </div>
                                        </ul>
                                    </div>
                                @elseif ($jumlahAATerisi >= 1 && $detailFiltered->count() > 0 && in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Group</th>
                                                        <th>Item</th>
                                                        <th>Value</th>
                                                        <th>CATATAN OPERATOR</th>
                                                        <th>Catatan MEKANIK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($detailFiltered as $dt)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><input type="hidden" name="CHECKLISTGROUPID[]" value="{{ $dt->CHECKLISTGROUPID }}">{{ $dt->CHECKLISTGROUPID }}</td>
                                                        <td><input type="hidden" name="CHECKLISTITEMDESCRIPTION[]" value="{{ $dt->CHECKLISTITEMDESCRIPTION }}"> {{ $dt->CHECKLISTITEMDESCRIPTION }}</td>
                                                        <td style="text-align: center">
                                                            <input type="hidden" name="CHECKLISTVAL[]" value="{{ $dt->CHECKLISTVAL }}">
                                                            @if ( $dt->CHECKLISTVAL == 0)
                                                            ❌
                                                            @elseif ( $dt->CHECKLISTVAL == 1)
                                                            ✔️
                                                            @else
                                                            -
                                                            {{-- 🔘 --}}
                                                            @endif
                                                        </td>
                                                        <td><input type="hidden" name="CHECKLISTNOTES[]" value="{{ $dt->CHECKLISTNOTES }}">{{ $dt->CHECKLISTNOTES }}</td>
                                                        <td><input type="text" class="form-control" style="min-width: 50px;" name="CATATAN_MEKANIK[]"></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-start">
                                            <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                        </div>
                                        <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" style="width: 100%; font-size:14pt; padding-left: 20px; padding-right: 20px;">
                                                    Verifikasi
                                                </button>
                                            </div>
                                        </ul>
                                    </div>
                                    @else
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Group</th>
                                                        <th rowspan="2">Item</th>
                                                        <th rowspan="2">Value</th>
                                                        <th rowspan="2">CATATAN OPERATOR</th>
                                                        @if (!$jumlahAATerisi >= 1 && !$verifikasiMekanik && !in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))
                                                        <th colspan="2">Komentar</th>
                                                        @endif
                                                    </tr>
                                                    @if (!$jumlahAATerisi >= 1 && !$verifikasiMekanik && !in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))
                                                    <tr>
                                                        <th>KBJ</th>
                                                        <th>Jawaban</th>
                                                    </tr>
                                                    @endif
                                                </thead>
                                                <tbody>
                                                    @foreach ($detail as $dt)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><input type="hidden" name="CHECKLISTGROUPID[]" value="{{ $dt->CHECKLISTGROUPID }}">{{ $dt->CHECKLISTGROUPID }}</td>
                                                        <td><input type="hidden" name="CHECKLISTITEMDESCRIPTION[]" value="{{ $dt->CHECKLISTITEMDESCRIPTION }}"> {{ $dt->CHECKLISTITEMDESCRIPTION }}</td>
                                                        <td style="text-align: center">
                                                            <input type="hidden" name="CHECKLISTVAL[]" value="{{ $dt->CHECKLISTVAL }}">
                                                            @if ( $dt->CHECKLISTVAL == 0)
                                                            ❌
                                                            @elseif ( $dt->CHECKLISTVAL == 1)
                                                            ✔️
                                                            @else
                                                            -
                                                            {{-- 🔘 --}}
                                                            @endif
                                                        </td>
                                                        <td><input type="hidden" name="CHECKLISTNOTES[]" value="{{ $dt->CHECKLISTNOTES }}">{{ $dt->CHECKLISTNOTES }}</td>
                                                        @if (!$jumlahAATerisi >= 1 && !$verifikasiMekanik && !in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))
                                                            <td><input type="text" class="form-control" style="min-width: 50px;" name="KBJ[]"></td>
                                                            <td><input type="text" class="form-control" style="min-width: 250px" name="JAWABAN[]"></td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
                                        <div class="text-start">
                                            <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                        </div>
                                       @if (!$jumlahAATerisi >= 1 && !$verifikasiMekanik && !in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK']))

                                        <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-primary" style="width: 100%; font-size:14pt; padding-left: 20px; padding-right: 20px;">
                                                    Verifikasi
                                                </button>
                                            </div>
                                        </ul>
                                        @endif
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
