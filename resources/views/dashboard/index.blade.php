@include('layout.head', ['title' => 'Dashboard'])
@include('layout.sidebar')
@include('layout.header')

<style>
    @media (max-width: 575.98px) {
    .card .card-body, .card .card-header {
        padding: 10px;
    }
    .row>* {
    margin-top: 0.1rem;
    }
}
</style>

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="mb-0 alert alert-primary alert-dismissible fade show">Semangat Pagi, {{ Auth::user()->name }}!</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-1">
            <h5 class="w-100">Fitur Pilihan</h5>
            @if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER']))
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-new.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Produksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-batubara.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Batu Bara</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('pengawas-pitstop.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Form Pit Stop</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('jobpending') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/job-creation.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Job Pending</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('p2h.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/worker.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">P2H Unit</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('kkh.all') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/ergonomic.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Verifikasi KKH</h6>
                            </div>
                        </div>
                    </a>
                </div>

            @else
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-new.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Harian Produksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-batubara.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Harian Batu Bara</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('form-pengawas-sap.show') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Laporan Inspeksi</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('jobpending') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/job-creation.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Job Pending</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('p2h.index') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/worker.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">P2H Unit</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-4 col-xxl-4">
                    <a href="{{ route('kkh.all') }}" class="text-decoration-none">
                        <div class="card mb-3">
                            <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                                <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/ergonomic.png" alt="Form Pengawas" style="max-width: 20px">
                                <h6 class="card-title" style="font-size:11px">Verifikasi KKH</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('production.index') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/production.png" alt="Produksi Per Jam" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Produksi Per Jam</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('payloadritation.exa') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/loading.png" alt="Payload & Ritation" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Payload & Ritation</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('production.ex') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/production-ex.png" alt="Produksi Per EX Ja" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Produksi EX Per Jam</h6>
                        </div>
                    </div>
                </a>
            </div>
            {{-- <div class="col-4 col-md-4 col-xxl-4">
                <a href="{{ route('monitoringpayload') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center" style="padding-left:2px; padding-right:2px;">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/kpi.png" alt="Monitoring Payload" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:11px">Monitoring Payload</h6>
                        </div>
                    </div>
                </a>
            </div> --}}
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <img
                        class="img-fluid me-2"
                        src="{{ asset('dashboard/assets/images/widget/to-do-list.png') }}"
                        alt="Logo KLKH"
                        style="max-width: 20px; height: auto;">
                    <h5 class="card-title mb-0">Form SAP</h5>
                </div>

                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('form-pengawas-sap.index') }}" class="list-group-item list-group-item-action">Inspeksi</a>
                        <a href="{{ route('klkh.loading-point') }}" class="list-group-item list-group-item-action">KLKH Loading Point</a>
                        <a href="{{ route('klkh.haul-road') }}" class="list-group-item list-group-item-action">KLKH Haul Road</a>
                        <a href="{{ route('klkh.disposal') }}" class="list-group-item list-group-item-action">KLKH Disposal/Dumping Point</a>
                        <a href="{{ route('klkh.lumpur') }}" class="list-group-item list-group-item-action">KLKH Dumping di Kolam Air/Lumpur</a>
                        <a href="{{ route('klkh.ogs') }}" class="list-group-item list-group-item-action">KLKH OGS</a>
                        <a href="{{ route('klkh.batubara') }}" class="list-group-item list-group-item-action">KLKH Batubara</a>
                        <a href="{{ route('klkh.simpangempat') }}" class="list-group-item list-group-item-action">KLKH INTERSECTION (Simpang Empat)</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row g-1">

            <h5 class="w-100">KLKH</h5>

            <div class="col-4 col-md-6 col-xxl-2">
                <a href="{{ route('klkh.loading-point') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="KLKH Loading Point" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:10px">Loading Point</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-4 col-md-6 col-xxl-2">
                <a href="{{ route('klkh.haul-road') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="KLKH Haul Road" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:12px">Haul Road</h6>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-4 col-md-6 col-xxl-2">
                <a href="{{ route('klkh.disposal') }}" class="text-decoration-none">
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <img class="img-fluid card-img-top" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="KLKH Disposal" style="max-width: 20px">
                            <h6 class="card-title" style="font-size:12px">Disposal</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div> --}}

    </div>
</div>



@include('layout.footer')
