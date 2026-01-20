@include('layout.head', ['title' => 'Produksi EX Per Jam'])
@include('layout.sidebar')
@include('layout.header')
<script src="{{ asset('dashboard/assets') }}/js/plugins/chart.js"></script>
<style>
    /* ===== KPI MOBILE STYLE ===== */
.kpi-mobile-item {
    background: #fff;
    border-radius: 10px;
    padding: 10px 12px;
}

.kpi-time {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 6px;
}

.kpi-progress .progress {
    height: 14px;
    border-radius: 8px;
    background: #f2f2f2;
}

.kpi-progress .progress-bar {
    font-size: 11px;
    font-weight: 600;
    line-height: 14px;
    border-radius: 8px;
}

.kpi-value {
    margin-top: 4px;
    font-size: 13px;
    font-weight: 600;
    color: #1f8f3a;
}
.chart-panel {
    background: #2b2d31;
    border-radius: 10px;
    padding: 14px 16px;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
}

.chart-title {
    color: #e5e7eb;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
}

.chart-dark canvas {
    background: #2b2d31;
}

/* ================= KPI RESPONSIVE ================= */

/* default: DESKTOP */
.kpi-mobile { display: none; }
.kpi-desktop { display: block; }

/* MOBILE ONLY */
@media (max-width: 767px) {
    .kpi-desktop { display: none; }
    .kpi-mobile { display: block; }

    .kpi-mobile-item {
        background: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .kpi-time {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .kpi-progress .progress {
        height: 14px;
        border-radius: 8px;
        background: #f2f2f2;
    }

    .kpi-progress .progress-bar {
        font-size: 11px;
        font-weight: 600;
        line-height: 14px;
        border-radius: 8px;
    }

    .kpi-value {
        margin-top: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #1f8f3a;
    }
}

    .staging-img {
        max-width: 700px;
        object-fit: contain;
    }

    /* Tablet */
    @media (max-width: 992px) {
        .staging-img {
            max-width: 320px;
        }
    }

    /* Mobile */
    @media (max-width: 576px) {
        .staging-img {
            max-width: 320px;
        }
    }

</style>
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
                            @php
                                $percent = $data['plan'] != 0
                                    ? ($data['actual'] / $data['plan']) * 100
                                    : 0;

                                if ($percent < 65) {
                                    $color = '#fb8078'; // merah
                                } elseif ($percent <= 85) {
                                    $color = '#ffa500'; // orange
                                } elseif ($percent <= 100) {
                                    $color = '#039201'; // hijau
                                } else {
                                    $color = '#4e7be6'; // biru
                                }

                                $percentFormatted = number_format($percent, 2);
                                $width = min($percent, 100); // supaya tidak overflow
                            @endphp

                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                    role="progressbar"
                                    style="width: {{ $width }}%; background-color: {{ $color }};"
                                    aria-valuenow="{{ $percentFormatted }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $percentFormatted }}%
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
                @php
                            // =========================
                            // SUMBER DATA EX
                            // =========================
                            $perExAktif = collect($data['kategori']['PerExAktif']);
                            $perExHistory = collect($data['kategori']['PerExHistory']);

                            // Group by EX
                            $allExIds = $data['kategori']['OrderedExIds'];

                            $perExAktifGrouped   = collect($data['kategori']['PerExAktif'])->groupBy('LOD_LOADERID');
                            $perExHistoryGrouped = collect($data['kategori']['PerExHistory'])->groupBy('LOD_LOADERID');
                            $jamSiang = [7,8,9,10,11,12,13,14,15,16,17,18];
                            $jamMalam = [19,20,21,22,23,0,1,2,3,4,5,6];


                            $chartExLabels = [];
                            $chartExProd   = [];
                            $chartExPlan   = [];

                            $grouped = collect($data['kategori']['PerExAktif'])
                                ->groupBy('LOD_LOADERID');

                            foreach ($allExIds as $ex) {
                                $rows = collect($grouped->get($ex, []));

                                $chartExLabels[] = $ex;
                                $chartExProd[] = round($rows->sum('PRODUCTION'));
                                $chartExPlan[] = round($rows->sum('PLAN_PRODUCTION'));
                            }
                            @endphp

                            {{-- <div class="chart-panel mb-3">
                                <div class="chart-title">Production per EXCA</div>
                                <div style="height:260px">
                                    <canvas id="chartPerExca"></canvas>
                                </div>
                            </div> --}}
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {

                                    const labels   = @json($chartExLabels);
                                    const actual  = @json($chartExProd);
                                    const plan    = @json($chartExPlan);

                                    const ctx = document.getElementById('chartPerExca');
                                    if (!ctx) return;

                                    new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels,
                                            datasets: [
                                                {
                                                    label: 'Actual',
                                                    data: actual,
                                                    backgroundColor: '#00b050',
                                                    borderRadius: 6,
                                                    barThickness: 22,
                                                    barThickness: 15,      // ketebalan bar (px)
                                                    maxBarThickness: 22
                                                }
                                            ]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            layout: { padding: { top: 24 } },
                                            plugins: {
                                                legend: {
                                                    labels: {
                                                        color: '#e5e7eb',
                                                        font: { weight: '600' }
                                                    }
                                                },
                                                tooltip: {
                                                    backgroundColor: '#111',
                                                    titleColor: '#fff',
                                                    bodyColor: '#fff',
                                                    padding: 8,
                                                    callbacks: {
                                                        label: c => ` ${c.raw}`
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    ticks: {
                                                        color: '#d1d5db',
                                                        font: { size: 11, weight: '600' }
                                                    },
                                                    grid: {
                                                        color: 'rgba(255,255,255,.08)'
                                                    }
                                                },
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        color: '#9ca3af',
                                                        font: { size: 11 }
                                                    },
                                                    grid: {
                                                        color: 'rgba(255,255,255,.08)'
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'BCM',
                                                        color: '#9ca3af'
                                                    }
                                                }
                                            }
                                        },
                                        plugins: [{
                                            /* ===== LABEL ANGKA DI ATAS BAR ===== */
                                            id: 'valueBox',
                                            afterDatasetsDraw(chart) {
                                                const { ctx } = chart;
                                                ctx.save();
                                                ctx.font = '10px Arial';
                                                ctx.textAlign = 'center';

                                                chart.data.datasets.forEach((ds, di) => {
                                                    const meta = chart.getDatasetMeta(di);
                                                    meta.data.forEach((bar, i) => {
                                                        const val = ds.data[i];
                                                        if (!val) return;

                                                        const x = bar.x;
                                                        const y = bar.y - 6;

                                                        ctx.fillStyle = '#fff';
                                                        ctx.fillRect(x - 16, y - 14, 32, 14);

                                                        ctx.fillStyle = '#000';
                                                        ctx.fillText(val, x, y - 3);
                                                    });
                                                });

                                                ctx.restore();
                                            }
                                        }]
                                    });

                                });
                            </script>
                @if($data['staging'])
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="card-header align-items-center justify-content-between">
                            <h5 class="mb-0">Staging Plan</h5>
                            <img src="{{ $data['staging']->image }}" alt="Staging Plan" class="staging-img">
                        </div>
                        <a href="{{ $data['staging']->image }}" class="btn btn-primary w-50" style="padding-top:5px;padding-bottom:5px;" download>
                            Download Gambar
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-xl-6 col-md-6">

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

                                        {{-- ================= TAB SIANG ================= --}}
                                        <div class="tab-pane fade @if($data['waktu']=='Siang') show active @endif"
                                            id="{{ $paneSiang }}">

                                        @php
                                        $rows = $data['waktu']=='Siang' ? $siangAktif : $siangHist;
                                        $map  = collect($rows)->keyBy(fn($r)=>(int)$r->HOUR);
                                        @endphp

                                        @foreach ($jamSiang as $h)
                                            @php
                                            $item = $map->get($h);

                                            $prod = $item->PRODUCTION ?? 0;
                                            $plan = $item->PLAN_PRODUCTION ?? 0;

                                            $percent = $plan > 0 ? ($prod/$plan)*100 : 0;

                                            if ($percent < 65) $color = '#fb8078';
                                            elseif ($percent <= 85) $color = '#ffa500';
                                            elseif ($percent <= 100) $color = '#039201';
                                            else $color = '#4e7be6';
                                            @endphp

                                            <div class="kpi-item">

                                            {{-- ===== DESKTOP MODE ===== --}}
                                                <div class="kpi-desktop">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-2 text-nowrap">
                                                            {{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="progress" style="height:18px">
                                                                <div class="progress-bar"
                                                                    style="width: {{ number_format($percent,2) }}%;
                                                                            background: {{ $color }}">
                                                                    {{ number_format($percent,2) }}%
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-2 text-end text-success">
                                                            {{ round($prod) }} / {{ round($plan) }}
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- ===== MOBILE MODE ===== --}}
                                                <div class="kpi-mobile">
                                                    <div class="kpi-mobile-item mb-3">
                                                        <div class="kpi-time">
                                                            {{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00
                                                        </div>

                                                        <div class="kpi-progress">
                                                            <div class="progress">
                                                                <div class="progress-bar"
                                                                    style="width: {{ number_format($percent,2) }}%;
                                                                            background: {{ $color }}">
                                                                    {{ number_format($percent,2) }}%
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="kpi-value">
                                                            {{ round($prod) }} / {{ round($plan) }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        @endforeach
                                        </div>


                                        {{-- ================= TAB MALAM ================= --}}
                                        <div class="tab-pane fade @if($data['waktu']=='Malam') show active @endif"
                                            id="{{ $paneMalam }}">

                                        @php
                                        $rows = $data['waktu']=='Malam' ? $malamAktif : $malamHist;
                                        $map  = collect($rows)->keyBy(fn($r)=>(int)$r->HOUR);
                                        @endphp

                                        @foreach ($jamMalam as $h)
                                        @php
                                        $item = $map->get($h);

                                        $prod = $item->PRODUCTION ?? 0;
                                        $plan = $item->PLAN_PRODUCTION ?? 0;

                                        $percent = $plan > 0 ? ($prod/$plan)*100 : 0;

                                        if ($percent < 65) $color = '#fb8078';
                                        elseif ($percent <= 85) $color = '#ffa500';
                                        elseif ($percent <= 100) $color = '#039201';
                                        else $color = '#4e7be6';
                                        @endphp

                                        <div class="kpi-item">

                                            {{-- ===== DESKTOP MODE ===== --}}
                                                <div class="kpi-desktop">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-2 text-nowrap">
                                                            {{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00
                                                        </div>

                                                        <div class="col-8">
                                                            <div class="progress" style="height:18px">
                                                                <div class="progress-bar"
                                                                    style="width: {{ number_format($percent,2) }}%;
                                                                            background: {{ $color }}">
                                                                    {{ number_format($percent,2) }}%
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-2 text-end text-success">
                                                            {{ round($prod) }} / {{ round($plan) }}
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- ===== MOBILE MODE ===== --}}
                                                <div class="kpi-mobile">
                                                    <div class="kpi-mobile-item mb-3">
                                                        <div class="kpi-time">
                                                            {{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00
                                                        </div>

                                                        <div class="kpi-progress">
                                                            <div class="progress">
                                                                <div class="progress-bar"
                                                                    style="width: {{ number_format($percent,2) }}%;
                                                                            background: {{ $color }}">
                                                                    {{ number_format($percent,2) }}%
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="kpi-value">
                                                            {{ round($prod) }} / {{ round($plan) }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                        </div>

                                        </div>


                                    </div>
                                </div>
                            </div>



                        @endforeach
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

