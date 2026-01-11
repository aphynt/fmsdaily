@include('layout.head', ['title' => 'Produksi Per Jam'])
@include('layout.sidebar')
@include('layout.header')
<script src="{{ asset('dashboard/assets') }}/js/plugins/apexcharts.min.js"></script>
<div class="pc-container">
    <div class="pc-content">

        {{-- ================= ROW UTAMA ================= --}}
        <div class="row">

            {{-- =========================================================
            | KOLOM KIRI
            ========================================================== --}}
            <div class="col-xl-6 col-md-6">

                {{-- ===== CARD SUMMARY ===== --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center my-3">
                            <div class="flex-shrink-0">
                                <div class="avtar avtar-s bg-light-success">
                                    <i class="ti ti-list-check f-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">Produksi Overburden</h5>
                            </div>
                        </div>

                        <div class="my-3">
                            <p class="mb-2">
                                Tasks done
                                <span class="float-end">
                                    {{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%
                                </span>
                            </p>

                            <div class="progress progress-primary">
                                <div class="progress-bar"
                                    style="width: {{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%">
                                    {{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <div class="btn btn-link-secondary">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 mx-2 text-start">Actual</div>
                                    <div class="badge bg-light-dark">
                                        {{ number_format($data['actual'], 0, ',', '.') }} BCM
                                    </div>
                                </div>
                            </div>

                            <div class="btn btn-link-secondary">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 mx-2 text-start">Plan</div>
                                    <div class="badge bg-light-dark">
                                        {{ number_format($data['plan'], 0, ',', '.') }} BCM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ===== ACCORDION PER EX (GRAPH MODE) ===== --}}
                @php
                // =========================
                // SUMBER DATA EX
                // =========================
                $perExAktif = collect($data['kategori']['PerExAktif']);
                $perExHistory = collect($data['kategori']['PerExHistory']);

                // Group by EX
                $perExAktifGrouped = $perExAktif->groupBy('LOD_LOADERID');
                $perExHistoryGrouped = $perExHistory->groupBy('LOD_LOADERID');

                // Gabungkan key EX agar semua EX muncul
                $allExIds = $perExAktifGrouped->keys()
                ->merge($perExHistoryGrouped->keys())
                ->unique();

                @endphp

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Productivity EXCA</h5>
                    </div>

                    <div class="card-body">
                        <div class="accordion" id="accordionPerExShift">

                        @foreach ($allExIds as $ex)
                            @php
                                // ===== FILTER EX NULL DI VIEW =====
                                $exVal = trim((string)$ex);
                            @endphp

                            @if ($exVal === '' || strtoupper($exVal) === 'NULL')
                                @continue
                            @endif

                            @php
                                $rowsAktif   = collect($perExAktifGrouped->get($ex, []));
                                $rowsHistory = collect($perExHistoryGrouped->get($ex, []));

                                $siangAktif = []; $malamAktif = [];
                                $siangHist  = []; $malamHist  = [];

                                foreach ($rowsAktif as $r) {
                                    $h = (int) $r->HOUR;
                                    if ($h >= 7 && $h <= 18) $siangAktif[] = $r;
                                    else $malamAktif[] = $r;
                                }

                                foreach ($rowsHistory as $r) {
                                    $h = (int) $r->HOUR;
                                    if ($h >= 7 && $h <= 18) $siangHist[] = $r;
                                    else $malamHist[] = $r;
                                }

                                $collapseId = 'collapse_ex_'.$loop->index;
                                $paneSiang  = 'pane_siang_'.$loop->index;
                                $paneMalam  = 'pane_malam_'.$loop->index;
                            @endphp

                            <div class="accordion-item mb-3 border rounded">

                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-semibold"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $collapseId }}">
                                        {{ $exVal }}
                                    </button>
                                </h2>

                                <div id="{{ $collapseId }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">

                                        {{-- ===== TAB HEADER ===== --}}
                                        <ul class="nav nav-pills mb-3">
                                            @if ($data['waktu']=='Siang')
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="pill"
                                                    href="#{{ $paneSiang }}">Shift Siang</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="pill"
                                                    href="#{{ $paneMalam }}">History Shift Malam</a>
                                                </li>
                                            @else
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="pill"
                                                    href="#{{ $paneMalam }}">Shift Malam</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="pill"
                                                    href="#{{ $paneSiang }}">History Shift Siang</a>
                                                </li>
                                            @endif
                                        </ul>

                                        {{-- ===== TAB CONTENT ===== --}}
                                        <div class="tab-content">
                                            <div class="tab-pane fade @if($data['waktu']=='Siang') show active @endif"
                                                id="{{ $paneSiang }}">
                                                <div id="chart_siang_{{ $loop->index }}"></div>
                                            </div>

                                            <div class="tab-pane fade @if($data['waktu']=='Malam') show active @endif"
                                                id="{{ $paneMalam }}">
                                                <div id="chart_malam_{{ $loop->index }}"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ================= SCRIPT CHART ================= --}}
                            <script>
                            (function () {

                                /* ================= DATA SIANG ================= */
                                const dataSiang_{{ $loop->index }} = @json(
                                    $data['waktu']=='Siang' ? $siangAktif : $siangHist
                                );
                                const orderSiang = [7,8,9,10,11,12,13,14,15,16,17,18];

                                const mapSiang = {};
                                dataSiang_{{ $loop->index }}.forEach(r => {
                                    mapSiang[parseInt(r.HOUR)] = r;
                                });

                                const labelsSiang_{{ $loop->index }} = [];
                                const prodSiang_{{ $loop->index }}   = [];

                                orderSiang.forEach(h => {
                                    if (mapSiang[h]) {
                                        labelsSiang_{{ $loop->index }}.push(String(h).padStart(2,'0'));
                                        prodSiang_{{ $loop->index }}.push(Math.round(mapSiang[h].PRODUCTION));
                                    }
                                });

                                if (document.querySelector('#chart_siang_{{ $loop->index }}')) {
                                    new ApexCharts(
                                        document.querySelector('#chart_siang_{{ $loop->index }}'),
                                        {
                                            chart: { type: 'bar', height: 260, toolbar: { show: false } },
                                            series: [{ name: 'Production', data: prodSiang_{{ $loop->index }} }],
                                            xaxis: { categories: labelsSiang_{{ $loop->index }}, title: { text: 'Jam' } },
                                            plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                                            dataLabels: {
                                                enabled: true,
                                                formatter: val => val === 0 ? '' : val
                                            },
                                            yaxis: {
                                                labels: { formatter: v => Math.round(v) },
                                                title: { text: 'Production' }
                                            }
                                        }
                                    ).render();
                                }

                                /* ================= DATA MALAM ================= */
                                const dataMalam_{{ $loop->index }} = @json(
                                    $data['waktu']=='Malam' ? $malamAktif : $malamHist
                                );
                                const orderMalam = [19,20,21,22,23,0,1,2,3,4,5,6];

                                const mapMalam = {};
                                dataMalam_{{ $loop->index }}.forEach(r => {
                                    mapMalam[parseInt(r.HOUR)] = r;
                                });

                                const labelsMalam_{{ $loop->index }} = [];
                                const prodMalam_{{ $loop->index }}   = [];

                                orderMalam.forEach(h => {
                                    if (mapMalam[h]) {
                                        labelsMalam_{{ $loop->index }}.push(String(h).padStart(2,'0'));
                                        prodMalam_{{ $loop->index }}.push(Math.round(mapMalam[h].PRODUCTION));
                                    }
                                });

                                if (document.querySelector('#chart_malam_{{ $loop->index }}')) {
                                    new ApexCharts(
                                        document.querySelector('#chart_malam_{{ $loop->index }}'),
                                        {
                                            chart: { type: 'bar', height: 260, toolbar: { show: false } },
                                            series: [{ name: 'Production', data: prodMalam_{{ $loop->index }} }],
                                            xaxis: { categories: labelsMalam_{{ $loop->index }}, title: { text: 'Jam' } },
                                            plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                                            dataLabels: {
                                                enabled: true,
                                                formatter: val => val === 0 ? '' : val
                                            },
                                            yaxis: {
                                                labels: { formatter: v => Math.round(v) },
                                                title: { text: 'Production' }
                                            }
                                        }
                                    ).render();
                                }

                            })();
                            </script>

                        @endforeach
                        </div>
                    </div>
                </div>




            </div>

            <div class="col-xl-6 col-md-6">

                <div class="card">
                    <div class="card-body pc-component">
                        {{-- <h5 class="mb-3">Basic Pills</h5> --}}
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            @if ($data['waktu'] == 'Siang')
                            <li class="nav-item"><a class="nav-link active" id="shift-siang-tab" data-bs-toggle="pill"
                                    href="#shift-siang" role="tab" aria-controls="shift-siang"
                                    aria-selected="true">Shift
                                    Siang</a>
                            </li>

                            <li class="nav-item"><a class="nav-link" id="history-shift-malam-tab" data-bs-toggle="pill"
                                    href="#history-shift-malam" role="tab" aria-controls="history-shift-malam"
                                    aria-selected="false">History Shift
                                    Malam</a></li>
                            @else
                            <li class="nav-item"><a class="nav-link active" id="shift-malam-tab" data-bs-toggle="pill"
                                    href="#shift-malam" role="tab" aria-controls="shift-malam"
                                    aria-selected="true">Shift
                                    Malam</a></li>

                            <li class="nav-item"><a class="nav-link" id="history-shift-siang-tab" data-bs-toggle="pill"
                                    href="#history-shift-siang" role="tab" aria-controls="history-shift-siang"
                                    aria-selected="false">History Shift
                                    Siang</a></li>
                            @endif
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show @if ($data['waktu'] == 'Siang') active @endif"
                                id="shift-siang" role="tabpanel" aria-labelledby="shift-siang-tab">
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body pc-component">
                                            @foreach ($data['kategori']['Siang'] as $item)
                                            <div class="row mb-4">
                                                <div class="col-12 col-md-2">
                                                    <label for="">{{ $item->HOUR }}:00</label>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="progress" style="height: 20px">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                            @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and
                                                                    number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85) background-color:#ffa500;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and
                                                                    number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100) background-color:#039201;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100) background-color:#4e7be6; @endif"
                                                            aria-valuenow="{{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <span style="color: green">{{ round($item->PRODUCTION) }} /
                                                        {{ round($item->PLAN_PRODUCTION) }}</span>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="history-shift-malam" role="tabpanel"
                                aria-labelledby="history-shift-malam-tab">
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body pc-component">
                                            @foreach ($data['kategori']['HistoryMalam'] as $item)
                                            <div class="row mb-4">
                                                <div class="col-12 col-md-2">
                                                    <label for="">{{ $item->HOUR }}:00</label>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="progress" style="height: 20px">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                                @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and
                                                                        number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85) background-color:#ffa500;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and
                                                                        number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100) background-color:#039201;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100) background-color:#4e7be6; @endif"
                                                            aria-valuenow="{{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <span style="color: green">{{ round($item->PRODUCTION) }} /
                                                        {{ round($item->PLAN_PRODUCTION) }}</span>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show @if ($data['waktu'] == 'Malam') active @endif"
                                id="shift-malam" role="tabpanel" aria-labelledby="shift-malam-tab">
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body pc-component">
                                            @foreach ($data['kategori']['Malam'] as $item)
                                            <div class="row mb-4">
                                                <div class="col-12 col-md-2">
                                                    <label for="">{{ $item->HOUR }}:00</label>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="progress" style="height: 20px">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                                @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and
                                                                        number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85) background-color:#ffa500;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and
                                                                        number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100) background-color:#039201;
                                                                @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100) background-color:#4e7be6; @endif"
                                                            aria-valuenow="{{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <span style="color: green">{{ round($item->PRODUCTION) }} /
                                                        {{ round($item->PLAN_PRODUCTION) }}</span>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="history-shift-siang" role="tabpanel"
                                aria-labelledby="history-shift-siang-tab">
                                <div class="col-xl-12 col-md-6">
                                    <div class="card">
                                        <div class="card-body pc-component">
                                            @foreach ($data['kategori']['HistorySiang'] as $item)
                                            <div class="row mb-4">
                                                <div class="col-12 col-md-2">
                                                    <label for="">{{ $item->HOUR }}:00</label>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="progress" style="height: 20px">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                            @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and
                                                                    number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85) background-color:#ffa500;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and
                                                                    number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100) background-color:#039201;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100) background-color:#4e7be6; @endif"
                                                            aria-valuenow="{{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <span style="color: green">{{ round($item->PRODUCTION) }} /
                                                        {{ round($item->PLAN_PRODUCTION) }}</span>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <h6>Keterangan:</h6>
                            <div class="col-6 col-sm-4 col-md-3 mb-4">
                                <div class="progress" style="height: 20px">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 100%;background-color:#fb8078" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100">
                                        < 65%</div> </div> </div> <div class="col-6 col-sm-4 col-md-3 mb-4">
                                            <div class="progress" style="height: 20px">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: 100%;background-color:#ffa500" aria-valuenow="100"
                                                    aria-valuemin="0" aria-valuemax="100">65 s/d 85 (%)</div>
                                            </div>
                                    </div>
                                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                                        <div class="progress" style="height: 20px">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: 100%;background-color:#039201" aria-valuenow="100"
                                                aria-valuemin="0" aria-valuemax="100">86 s/d 100 (%)</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4 col-md-3 mb-4">
                                        <div class="progress" style="height: 20px">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: 100%;background-color:#4e7be6" aria-valuenow="100"
                                                aria-valuemin="0" aria-valuemax="100">> 100 (%)</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div> {{-- END ROW --}}
        </div>
    </div>

    @include('layout.footer')

    <script>
        setTimeout(function () {
            location.reload();
        }, 300000); // 300000 ms = 5 menit

    </script>
    <script>
        function getUrutanJam(mode) {
            if (mode === 'Malam') {
                // 19 → 23, lalu 00 → 06
                return [19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6];
            }
            // Siang: 07 → 18
            return [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
        }

    </script>
    <script>
        "use strict";
        const categories = [
            "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00",
            "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00",
            "22:00", "23:00"
        ];

        const kategori = {
            "Siang": [],
            "Malam": []
        };

        categories.forEach(time => {
            const hour = parseInt(time.split(":")[0]); // Mendapatkan jam (0-23)

            if (hour >= 7 && hour <= 18) {
                kategori.Siang.push(time);
            } else {
                kategori.Malam.push(time);
            }
        });

    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

