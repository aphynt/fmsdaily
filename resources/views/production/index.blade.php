@include('layout.head', ['title' => 'Produksi Per Jam'])
@include('layout.sidebar')
@include('layout.header')

<div class="pc-container">
    <div class="pc-content">

        <div class="row">
            <div id="notifier" class="notifier-container">
                <span id="notification-message"></span>

            </div>
            <!-- [ sample-page ] start -->
            {{-- <div class="col-lg-12 col-xxl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Data Produksi Per Jam</h5>
                            <p class="mb-0">{{ now()->timezone('Asia/Makassar')->format('l, d F Y') }} WITA</p>
        </div>
        <div id="production-per-hour-chart"></div>
    </div>
</div>
</div> --}}
<div class="col-xl-4 col-md-6">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center my-3">
                <div class="flex-shrink-0">
                    <div class="avtar avtar-s bg-light-success"><i class="ti ti-list-check f-20"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="mb-0">Produksi Overburden</h5>
                </div>
            </div>
            <div class="my-3">
                <p class="mb-2">Tasks done <span
                        class="float-end">{{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%</span>
                </p>
                <div class="progress progress-primary">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%"
                        aria-valuenow="{{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}"
                        aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($data['plan'] != 0 ? ($data['actual'] / $data['plan']) * 100 : 0, 2) }}%
                    </div>
                </div>
            </div>
            <div class="d-grid gap-3"><a href="#" class="btn btn-link-secondary">
                    <div class="d-flex align-items-center">
                        {{-- <div class="flex-shrink-0"><span class="p-1 d-block bg-warning rounded-circle"></span></div> --}}
                        <div class="flex-grow-1 mx-2">
                            <p class="mb-0 d-grid text-start"><span class="text-truncate w-100">Actual</span></p>
                        </div>
                        <div class="badge bg-light-dark f-12"> {{ number_format($data['actual'], 0, ',', '.') }} BCM
                        </div>
                    </div>
                </a><a href="#" class="btn btn-link-secondary">
                    <div class="d-flex align-items-center">
                        {{-- <div class="flex-shrink-0"><span class="p-1 d-block bg-primary rounded-circle"></span></div> --}}
                        <div class="flex-grow-1 mx-2">
                            <p class="mb-0 d-grid text-start"><span class="text-truncate w-100">Plan</span></p>
                        </div>
                        <div class="badge bg-light-dark f-12"> {{ number_format($data['plan'], 0, ',', '.') }} BCM
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

</div>
<div class="col-xl-8 col-md-6">
    <div class="card">
        <div class="card-body pc-component">
            {{-- <h5 class="mb-3">Basic Pills</h5> --}}
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @if ($data['waktu'] == 'Siang')
                <li class="nav-item"><a class="nav-link active" id="shift-siang-tab" data-bs-toggle="pill"
                        href="#shift-siang" role="tab" aria-controls="shift-siang" aria-selected="true">Shift Siang</a>
                </li>

                <li class="nav-item"><a class="nav-link" id="history-shift-malam-tab" data-bs-toggle="pill"
                        href="#history-shift-malam" role="tab" aria-controls="history-shift-malam"
                        aria-selected="false">History Shift Malam</a></li>
                @else
                <li class="nav-item"><a class="nav-link active" id="shift-malam-tab" data-bs-toggle="pill"
                        href="#shift-malam" role="tab" aria-controls="shift-malam" aria-selected="true">Shift
                        Malam</a></li>

                <li class="nav-item"><a class="nav-link" id="history-shift-siang-tab" data-bs-toggle="pill"
                        href="#history-shift-siang" role="tab" aria-controls="history-shift-siang"
                        aria-selected="false">History Shift Siang</a></li>
                @endif
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show @if ($data['waktu'] == 'Siang') active @endif" id="shift-siang"
                    role="tabpanel" aria-labelledby="shift-siang-tab">
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
                                            <div class="progress-bar" role="progressbar" style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                        @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85 ) background-color:#ffa500;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100 ) background-color:#039201;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100 ) background-color:#4e7be6;
                                                        @endif"
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
                                            <div class="progress-bar" role="progressbar" style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                            @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85 ) background-color:#ffa500;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100 ) background-color:#039201;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100 ) background-color:#4e7be6;
                                                            @endif"
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
                <div class="tab-pane fade show @if ($data['waktu'] == 'Malam') active @endif" id="shift-malam"
                    role="tabpanel" aria-labelledby="shift-malam-tab">
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
                                            <div class="progress-bar" role="progressbar" style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                            @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85 ) background-color:#ffa500;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100 ) background-color:#039201;
                                                            @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100 ) background-color:#4e7be6;
                                                            @endif"
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
                <div class="tab-pane fade show" id="history-shift-siang"
                    role="tabpanel" aria-labelledby="history-shift-siang-tab">
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
                                            <div class="progress-bar" role="progressbar" style="width: {{ number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) }}%;
                                                        @if (number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) < 65) background-color:#fb8078;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) >= 65 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 85 ) background-color:#ffa500;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 85 and number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) <= 100 ) background-color:#039201;
                                                        @elseif(number_format($item->PLAN_PRODUCTION != 0 ? ($item->PRODUCTION / $item->PLAN_PRODUCTION) * 100 : 0, 2) > 100 ) background-color:#4e7be6;
                                                        @endif"
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
                        <div class="progress-bar" role="progressbar" style="width: 100%;background-color:#fb8078"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">< 65%</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="progress" style="height: 20px">
                        <div class="progress-bar" role="progressbar" style="width: 100%;background-color:#ffa500"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">65 s/d 85 (%)</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="progress" style="height: 20px">
                        <div class="progress-bar" role="progressbar" style="width: 100%;background-color:#039201"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">86 s/d 100 (%)</div>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 mb-4">
                    <div class="progress" style="height: 20px">
                        <div class="progress-bar" role="progressbar" style="width: 100%;background-color:#4e7be6"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">> 100 (%)</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div><!-- [ Main Content ] end -->
</div>
</div>

@include('layout.footer')
<script>
    setTimeout(function () {
        location.reload();
    }, 300000); // 300000 ms = 5 menit

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
