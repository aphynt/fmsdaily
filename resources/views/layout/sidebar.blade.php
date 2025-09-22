<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header"><a href="#" class="b-brand text-primary">
                <img src="{{ asset('dashboard/assets') }}/images/icon.png" class="img-fluid" width="100px" alt="logo">
                <span class="badge bg-light-success rounded-pill ms-2 theme-version">{{ config('app.name') }}</span></a></div>
        <div class="navbar-content">
            <a style="color:#001932;" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                <div class="card pc-user-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><img src="{{ asset('dashboard/assets') }}/images/user/avatar-1.png"
                                    alt="user-image" class="user-avtar wid-45 rounded-circle"></div>
                            <div class="flex-grow-1 ms-3 me-2">
                                <h6 class="mb-0" style="font-size: 12px">{{ Auth::user()->name }}</h6><small>{{ Auth::user()->role }}</small>
                            </div><svg class="pc-icon">
                                    <use xlink:href="#custom-sort-outline"></use>
                                </svg>
                        </div>

                        <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                            <div class="pt-3">
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#changePassword"><svg class="pc-icon text-muted me-2"> <use xlink:href="#custom-share-bold"></use> </svg> <span>Ganti Password</span></a>
                                <a href="#!"><i class="ti ti-settings"></i><span>Profil</span></a>
                                <a href="{{ route('logout') }}"><i class="ti ti-power"></i><span>Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <ul class="pc-navbar">
                <li class="pc-item pc-caption"><label>Navigation</label></li>
                @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY', 'TRAINING CENTER']))
                <li class="pc-item"><a href="{{ route('dashboard.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/house.png" alt="NT"></span><span class="pc-mtext">Home</span></a></li>
                @endif
                <li class="pc-item"><a href="{{ route('production.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/production.png" alt="NT"></span><span class="pc-mtext">Produksi Per Jam</span></a></li>
                @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY']))
                <li class="pc-item"><a href="{{ route('payloadritation.exa') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/loading.png" alt="NT"></span><span class="pc-mtext">Payload & Ritation</span></a></li>
                @endif
                <li class="pc-item"><a href="{{ route('monitoringpayload') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/kpi.png" alt="NT"></span><span class="pc-mtext">Monitoring Payload</span></a></li>
                @if (!in_array(Auth::user()->role, ['TRAINING CENTER']))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/dashboard.png" alt="DS"> </span><span class="pc-mtext">Dashboard</span> <span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span> <span class="pc-badge">4</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item pc-hasmenu"><a href="#!" class="pc-link">Produksi<span
                                    class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="{{ route('front-loading.index') }}">Front Loading</a></li>
                                <li class="pc-item"><a class="pc-link" href="{{ route('alat-support.index') }}">Alat Support</a></li>
                                <li class="pc-item"><a class="pc-link" href="{{ route('catatan-pengawas.index') }}">Catatan Pengawas</a></li>
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu"><a href="#!" class="pc-link">Batu Bara<span
                                    class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.loading-point.index') }}">Loading Point</a></li>
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.unit-support.index') }}">Unit Support</a></li>
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.catatan-pengawas.index') }}">Catatan Pengawas</a></li>
                            </ul>
                        </li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('pengawas-pitstop.operator') }}">Pitstop</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.jamMonitor') }}">Kata Sandi</a></li>
                </li>

                    </ul>
                </li>

                @endif
                @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY']))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/sop.png" alt="DS"> </span><span class="pc-mtext">SOP Produksi</span> <span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span> <span class="pc-badge">3
                                </span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="{{ route('sop.perawatanPenimbunanJalan') }}">30. Perawatan dan Penimbunan Jalan</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('sop.penimbunanMaterialKolamLumpurBullDozer') }}">51. Penimbunan Material di Kolam Lumpur dengan Bull Dozer</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('sop.pemuatanPengangkutanLumpur') }}">52. Pemuatan dan Pengangkutan Lumpur</a></li>
                    </ul>
                </li>

                @endif
                @if (!in_array(Auth::user()->role, ['TRAINING CENTER']))
                <li class="pc-item pc-caption"><label>Laporan Harian</label> <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                    </svg>
                </li>
                @endif
                @if (!in_array(Auth::user()->role, ['SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY', 'TRAINING CENTER']))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/list.png" alt="DS"> </span><span class="pc-mtext">Daftar Laporan</span> <span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span> <span class="pc-badge">
                                    @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK']))
                                    7
                                    @else
                                    1
                                    @endif
                                </span>
                    </a>
                    <ul class="pc-submenu">
                        @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK']))
                        <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-new.show') }}">Pengawas Produksi</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-batubara.show') }}">Pengawas Batu Bara</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('pengawas-pitstop.show') }}">Pengawas Pitstop</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-sap.show') }}">Laporan Inspeksi</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.show') }}">Laporan Kata Sandi</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('jobpending.detail') }}">Laporan Job Pending</a></li>
                        @endif
                        <li class="pc-item"><a class="pc-link" href="{{ route('p2h.show') }}">Laporan P2H</a></li>
                    </ul>
                </li>
                @endif
                @if (!in_array(Auth::user()->role, ['ADMIN', 'MANAGER', 'FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY', 'TRAINING CENTER']))
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                            <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="DS"> </span><span class="pc-mtext">Form Laporan Kerja</span> <span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span> <span class="pc-badge">4</span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-new.index') }}">Pengawas Produksi</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-batubara.index') }}">Pengawas Batu Bara</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('pengawas-pitstop.index') }}">Pengawas Pitstop</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.index') }}">Laporan Kata Sandi</a></li>
                        </ul>
                    </li>
                    {{-- <li class="pc-item"><a href="{{ route('form-pengawas-sap.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/kpi.png" alt="NT"></span><span class="pc-mtext">Form SAP</span></a></li> --}}
                @endif
                @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'TRAINING CENTER']))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="DS"> </span><span class="pc-mtext">Form SAP</span> <span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span> <span class="pc-badge">8</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-sap.index') }}">Inspeksi</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.loading-point') }}">KLKH Loading Point</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.haul-road') }}">KLKH Haul Road</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.disposal') }}">KLKH Disposal/Dumping Point</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.lumpur') }}">KLKH Dumping di Kolam Air/Lumpur</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.ogs') }}">KLKH OGS</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.batubara') }}">KLKH Batu Bara</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('klkh.simpangempat') }}">KLKH Intersection (Simpang Empat)</a></li>
                    </ul>
                </li>
                @endif
                @if (!in_array(Auth::user()->role, ['FOREMAN', 'MANAGER', 'FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'TRAINING CENTER']))
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link"><span class="pc-micon">
                            <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/stamp.png" alt="DS"> </span><span class="pc-mtext">Verifikasi</span> <span class="pc-arrow"><i
                                    data-feather="chevron-right"></i></span> <span class="pc-badge">@if (in_array(Auth::user()->role, ['ADMIN', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY'])) 2 @else 1 @endif</span>
                        </a>
                        <ul class="pc-submenu">
                            {{-- <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.laporankerja') }}">Laporan Kerja</a></li> --}}
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh') }}">KLKH</a></li>
                            @if (in_array(Auth::user()->role, ['ADMIN', 'SUPERINTENDENT SAFETY', 'SUPERVISOR SAFETY', 'FOREMAN SAFETY']))
                            <li class="pc-item"><a class="pc-link" href="{{ route('monitoring.p2h') }}">P2H</a></li>
                            @endif
                            {{-- <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.haulroad') }}">Haul Road</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.disposal') }}">Disposal/Dumping Point</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.lumpur') }}">Dumping di Kolam Air/Lumpur</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.ogs') }}">OGS</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.batubara') }}">Batu Bara</a></li>
                            <li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh.simpangempat') }}">Intersection (Simpang Empat)</a></li> --}}
                        </ul>
                    </li>
                @endif
                @if (!in_array(Auth::user()->role, ['TRAINING CENTER']))
                <li class="pc-item"><a href="{{ route('p2h.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/worker.png" alt="NT"></span><span class="pc-mtext">P2H Unit</span></a></li>
                @endif
                @if (!in_array(Auth::user()->role, ['FOREMAN MEKANIK', 'PJS FOREMAN MEKANIK', 'JR FOREMAN MEKANIK', 'SUPERVISOR MEKANIK', 'LEADER MEKANIK', 'TRAINING CENTER']))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/ergonomic.png" alt="DS"> </span><span class="pc-mtext">KKH Produksi</span> <span class="pc-arrow"><i
                                data-feather="chevron-right"></i></span> <span class="pc-badge">2</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item"><a class="pc-link" href="{{ route('kkh.all') }}">Seleksi per Tanggal</a></li>
                        <li class="pc-item"><a class="pc-link" href="{{ route('kkh.name') }}">Seleksi per Nama</a></li>
                    </ul>
                </li>
                @endif
                @if (in_array(Auth::user()->role, ['FOREMAN', 'SUPERVISOR', 'SUPERINTENDENT']))
                <li class="pc-item"><a href="{{ route('jobpending') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/job-creation.png" alt="NT"></span><span class="pc-mtext">Job Pending</span></a></li>
                @endif
                @if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER']))
                    <li class="pc-item"><a href="{{ route('rosterkerja') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/project.png" alt="NT"></span><span class="pc-mtext">Roster Kerja</span></a></li>
                    <li class="pc-item"><a href="{{ route('monitoringlaporankerjaklkh') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/spyware.png" alt="NT"></span><span class="pc-mtext">Monitoring LK & KLKH</span></a></li>
                @endif
                @if (in_array(Auth::user()->role, ['ADMIN']))
                    <li class="pc-item pc-caption"><label>Configuration</label> <svg class="pc-icon">
                        <use xlink:href="#custom-presentation-chart"></use>
                        </svg>
                    </li>
                    <li class="pc-item"><a href="{{ route('user.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/user.png" alt="NT"></span><span class="pc-mtext">Users</span></a></li>
                    <li class="pc-item"><a href="{{ route('log.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/log.png" alt="NT"></span><span class="pc-mtext">Logging User</span></a></li>
                    <li class="pc-item pc-caption"><label></label>
                        <svg class="pc-icon">
                            <use xlink:href="#custom-presentation-chart"></use>
                        </svg>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@include('layout.modal.change-password')
