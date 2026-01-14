@include('layout.head', ['title' => 'Dashboard'])
@include('layout.sidebar')
@include('layout.header')

<style>
    @media (max-width: 575.98px) {
        .card .card-body, .card .card-header { padding: 10px; }
        .row>* { margin-top: 0.1rem; }
    }
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="mb-0 alert alert-primary alert-dismissible fade show">
                                Semangat Pagi, {{ Auth::user()->name }}!
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-1">
            <h5 class="w-100">Fitur Pilihan</h5>

            {{-- BLOK UTAMA: sebelumnya pakai role, sekarang pakai akses --}}
            @if (
                canAccess('form-pengawas-new.index') ||
                canAccess('form-pengawas-batubara.index') ||
                canAccess('pengawas-pitstop.index')
            )

                {{-- FORM INPUT --}}
                @if (canAccess('form-pengawas-new.index'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-new.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Produksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if (canAccess('form-pengawas-batubara.index'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-batubara.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Batu Bara</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if (canAccess('pengawas-pitstop.index'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('pengawas-pitstop.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Pit Stop</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

            @else

                {{-- BLOK LAPORAN (MANAGER / ADMIN sebelumnya) --}}
                @if (canAccess('form-pengawas-new.show'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-new.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Harian Produksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if (canAccess('form-pengawas-batubara.show'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-batubara.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Harian Batu Bara</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if (canAccess('form-pengawas-sap.show'))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-sap.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Inspeksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

            @endif

            {{-- MENU UMUM --}}
            @if (canAccess('jobpending'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('jobpending') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/job-creation.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Job Pending</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if (canAccess('p2h.index'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('p2h.index') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/worker.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">P2H Unit</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if (canAccess('kkh.all'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('kkh.all') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/ergonomic.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Verifikasi KKH</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if (canAccess('production.index'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('production.index') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/production.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Produksi Per Jam</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if (canAccess('payloadritation.exa'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('payloadritation.exa') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/loading.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Payload & Ritation</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if (canAccess('production.ex'))
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('production.ex') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/production-ex.png" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Produksi EX Per Jam</h6>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>

        {{-- FORM SAP LIST --}}
        @if (
            canAccess('form-pengawas-sap.index') ||
            canAccess('klkh.loading-point') ||
            canAccess('klkh.haul-road') ||
            canAccess('klkh.disposal') ||
            canAccess('klkh.lumpur') ||
            canAccess('klkh.ogs') ||
            canAccess('klkh.batubara') ||
            canAccess('klkh.simpangempat')
        )
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <img class="img-fluid me-2" src="{{ asset('dashboard/assets/images/widget/to-do-list.png') }}" style="max-width: 20px;">
                    <h5 class="card-title mb-0">Form SAP</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @if (canAccess('form-pengawas-sap.index'))<a href="{{ route('form-pengawas-sap.index') }}" class="list-group-item">Inspeksi</a>@endif
                        @if (canAccess('klkh.loading-point'))<a href="{{ route('klkh.loading-point') }}" class="list-group-item">KLKH Loading Point</a>@endif
                        @if (canAccess('klkh.haul-road'))<a href="{{ route('klkh.haul-road') }}" class="list-group-item">KLKH Haul Road</a>@endif
                        @if (canAccess('klkh.disposal'))<a href="{{ route('klkh.disposal') }}" class="list-group-item">KLKH Disposal/Dumping Point</a>@endif
                        @if (canAccess('klkh.lumpur'))<a href="{{ route('klkh.lumpur') }}" class="list-group-item">KLKH Dumping di Kolam Air/Lumpur</a>@endif
                        @if (canAccess('klkh.ogs'))<a href="{{ route('klkh.ogs') }}" class="list-group-item">KLKH OGS</a>@endif
                        @if (canAccess('klkh.batubara'))<a href="{{ route('klkh.batubara') }}" class="list-group-item">KLKH Batubara</a>@endif
                        @if (canAccess('klkh.simpangempat'))<a href="{{ route('klkh.simpangempat') }}" class="list-group-item">KLKH INTERSECTION (Simpang Empat)</a>@endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@include('layout.footer')
