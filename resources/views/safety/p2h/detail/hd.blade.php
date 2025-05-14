@include('layout.head', ['title' => 'Form P2H Unit'])
@include('layout.sidebar')
@include('layout.header')
@php
use Carbon\Carbon;
@endphp
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
                                        <h6>FM-SHE-26/09/08/05/25</h6>
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

                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="text-center">
                                                <tr>
                                                    <th rowspan="2">No</th>
                                                    <th rowspan="2">Group</th>
                                                    <th rowspan="2">Item</th>
                                                    <th rowspan="2">Value</th>
                                                    <th rowspan="2">Notes</th>
                                                    <th colspan="2">Komentar</th>
                                                </tr>
                                                <tr>
                                                    <th>KBJ</th>
                                                    <th>Jawaban</th>
                                                </tr>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
