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
                                <h6 class="mb-0" style="font-size: 12px">{{ Auth::user()->name }}</h6>
                                <small>{{ Auth::user()->roleRel->name }}</small>
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

                {{-- HOME --}}
                @if (canAccess('dashboard.index'))
                <li class="pc-item"><a href="{{ route('dashboard.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/house.png" alt="NT"></span><span class="pc-mtext">Home</span></a></li>
                @endif

                {{-- PRODUKSI --}}
                @if (canAccess('production.index'))
                <li class="pc-item"><a href="{{ route('production.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/production.png" alt="NT"></span><span class="pc-mtext">Produksi Per Jam</span></a></li>
                @endif
                @if (canAccess('production.ex'))
                <li class="pc-item"><a href="{{ route('production.ex') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/production-ex.png" alt="NT"></span><span class="pc-mtext">Produksi EX Per Jam</span></a></li>
                @endif

                {{-- PAYLOAD --}}
                @if (canAccess('payloadritation.exa'))
                <li class="pc-item"><a href="{{ route('payloadritation.exa') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/loading.png" alt="NT"></span><span class="pc-mtext">Payload & Ritation</span></a></li>
                @endif

                {{-- MONITORING PAYLOAD --}}
                @if (canAccess('monitoringpayload'))
                <li class="pc-item"><a href="{{ route('monitoringpayload') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/kpi.png" alt="NT"></span><span class="pc-mtext">Monitoring Payload</span></a></li>
                @endif

                {{-- DASHBOARD --}}
                @if (
                    canAccess('front-loading.index') ||
                    canAccess('alat-support.index') ||
                    canAccess('catatan-pengawas.index') ||
                    canAccess('bb.loading-point.index') ||
                    canAccess('bb.unit-support.index') ||
                    canAccess('bb.catatan-pengawas.index') ||
                    canAccess('pengawas-pitstop.operator') ||
                    canAccess('laporan-kata-sandi.jamMonitor')
                )
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/dashboard.png" alt="DS"> </span><span class="pc-mtext">Dashboard</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span> <span class="pc-badge">4</span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item pc-hasmenu"><a href="#!" class="pc-link">Produksi<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                @if (canAccess('front-loading.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('front-loading.index') }}">Front Loading</a></li>
                                @endif
                                @if (canAccess('alat-support.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('alat-support.index') }}">Alat Support</a></li>
                                @endif
                                @if (canAccess('catatan-pengawas.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('catatan-pengawas.index') }}">Catatan Pengawas</a></li>
                                @endif
                            </ul>
                        </li>
                        <li class="pc-item pc-hasmenu"><a href="#!" class="pc-link">Batu Bara<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="pc-submenu">
                                @if (canAccess('bb.loading-point.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.loading-point.index') }}">Loading Point</a></li>
                                @endif
                                @if (canAccess('bb.unit-support.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.unit-support.index') }}">Unit Support</a></li>
                                @endif
                                @if (canAccess('bb.catatan-pengawas.index'))
                                <li class="pc-item"><a class="pc-link" href="{{ route('bb.catatan-pengawas.index') }}">Catatan Pengawas</a></li>
                                @endif
                            </ul>
                        </li>
                        @if (canAccess('pengawas-pitstop.operator'))
                        <li class="pc-item"><a class="pc-link" href="{{ route('pengawas-pitstop.operator') }}">Pitstop</a></li>
                        @endif
                        @if (canAccess('laporan-kata-sandi.jamMonitor'))
                        <li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.jamMonitor') }}">Kata Sandi</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- SOP PRODUKSI --}}
                @if (
                    canAccess('sop.kegiatanDropCut') ||
                    canAccess('sop.kegiatanHaulRoad') ||
                    canAccess('sop.pengoperasianEXDigger') ||
                    canAccess('sop.pengoperasianLampuTambang') ||
                    canAccess('sop.pengelolaanWasteDump') ||
                    canAccess('sop.landClearing') ||
                    canAccess('sop.topSoil') ||
                    canAccess('sop.pengecekanPerbaikanWeakpoint') ||
                    canAccess('sop.penangananUnitHDAmblas') ||
                    canAccess('sop.perawatanPenimbunanJalan') ||
                    canAccess('sop.dumpingAreaWasteDump') ||
                    canAccess('sop.piketJagaTambang') ||
                    canAccess('sop.optimalisasiGantiShift') ||
                    canAccess('sop.perbaikanTanggulJalan') ||
                    canAccess('sop.coalGetting') ||
                    canAccess('sop.penimbunanMaterialKolamLumpurBullDozer') ||
                    canAccess('sop.pemuatanPengangkutanLumpur') ||
                    canAccess('sop.kegiatanSlippery')
                )
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/sop.png" alt="DS"> </span><span class="pc-mtext">SOP Produksi</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span> <span class="pc-badge">18</span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('sop.kegiatanDropCut'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.kegiatanDropCut') }}">01. Kegiatan Drop Cut</a></li>@endif
                        @if (canAccess('sop.kegiatanHaulRoad'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.kegiatanHaulRoad') }}">02. Kegiatan Haul Road</a></li>@endif
                        @if (canAccess('sop.pengoperasianEXDigger'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.pengoperasianEXDigger') }}">04. Pengoperasian Excavator Digger</a></li>@endif
                        @if (canAccess('sop.pengoperasianLampuTambang'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.pengoperasianLampuTambang') }}">09. Pengoperasian Lampu Tambang</a></li>@endif
                        @if (canAccess('sop.pengelolaanWasteDump'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.pengelolaanWasteDump') }}">10. Pengelolaan Waste Dump</a></li>@endif
                        @if (canAccess('sop.landClearing'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.landClearing') }}">11. Land Clearing</a></li>@endif
                        @if (canAccess('sop.topSoil'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.topSoil') }}">12. Top Soil</a></li>@endif
                        @if (canAccess('sop.pengecekanPerbaikanWeakpoint'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.pengecekanPerbaikanWeakpoint') }}">21. Pengecekan dan Perbaikan Weakpoint</a></li>@endif
                        @if (canAccess('sop.penangananUnitHDAmblas'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.penangananUnitHDAmblas') }}">25. Penanganan Unit HD Amblas Di Tambang</a></li>@endif
                        @if (canAccess('sop.perawatanPenimbunanJalan'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.perawatanPenimbunanJalan') }}">30. Perawatan dan Penimbunan Jalan</a></li>@endif
                        @if (canAccess('sop.dumpingAreaWasteDump'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.dumpingAreaWasteDump') }}">31. Dumping di Area Waste Dump</a></li>@endif
                        @if (canAccess('sop.piketJagaTambang'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.piketJagaTambang') }}">33. Piket Jaga Tambang</a></li>@endif
                        @if (canAccess('sop.optimalisasiGantiShift'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.optimalisasiGantiShift') }}">36. Optimalisasi Ganti Shift</a></li>@endif
                        @if (canAccess('sop.perbaikanTanggulJalan'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.perbaikanTanggulJalan') }}">43. Perbaikan Tanggul Jalan</a></li>@endif
                        @if (canAccess('sop.coalGetting'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.coalGetting') }}">47. Coal Getting</a></li>@endif
                        @if (canAccess('sop.penimbunanMaterialKolamLumpurBullDozer'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.penimbunanMaterialKolamLumpurBullDozer') }}">51. Penimbunan Material di Kolam Lumpur dengan Bull Dozer</a></li>@endif
                        @if (canAccess('sop.pemuatanPengangkutanLumpur'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.pemuatanPengangkutanLumpur') }}">52. Pemuatan dan Pengangkutan Lumpur</a></li>@endif
                        @if (canAccess('sop.kegiatanSlippery'))<li class="pc-item"><a class="pc-link" href="{{ route('sop.kegiatanSlippery') }}">53. Kegiatan Slippery</a></li>@endif
                    </ul>
                </li>
                @endif
                <li class="pc-item pc-caption"><label>Laporan Harian</label></li>
                {{-- LAPORAN HARIAN --}}
                @if (
                    canAccess('pengawas-produksi-pitstop.index') ||
                    canAccess('form-pengawas-batubara.show') ||
                    canAccess('form-pengawas-sap.show') ||
                    canAccess('laporan-kata-sandi.show') ||
                    canAccess('jobpending.detail') ||
                    canAccess('p2h.show')
                )

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/list.png" alt="DS"> </span><span class="pc-mtext">Daftar Laporan</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('pengawas-produksi-pitstop.index'))<li class="pc-item"><a class="pc-link" href="{{ route('pengawas-produksi-pitstop.index') }}">Pengawas OB</a></li>@endif
                        @if (canAccess('form-pengawas-batubara.show'))<li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-batubara.show') }}">Pengawas Coal</a></li>@endif
                        @if (canAccess('form-pengawas-sap.show'))<li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-sap.show') }}">Laporan Inspeksi</a></li>@endif
                        @if (canAccess('laporan-kata-sandi.show'))<li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.show') }}">Laporan Kata Sandi</a></li>@endif
                        @if (canAccess('jobpending.detail'))<li class="pc-item"><a class="pc-link" href="{{ route('jobpending.detail') }}">Laporan Job Pending</a></li>@endif
                        @if (canAccess('p2h.show'))<li class="pc-item"><a class="pc-link" href="{{ route('p2h.show') }}">Laporan P2H</a></li>@endif
                    </ul>
                </li>
                @endif

                {{-- FORM LAPORAN KERJA --}}
                @if (
                    canAccess('form-pengawas-new.index') ||
                    canAccess('form-pengawas-batubara.index') ||
                    canAccess('pengawas-pitstop.index') ||
                    canAccess('laporan-kata-sandi.index')
                )
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/pencil.png" alt="DS"> </span><span class="pc-mtext">Form Laporan Kerja</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span> <span class="pc-badge">4</span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('form-pengawas-new.index'))<li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-new.index') }}">Pengawas Produksi</a></li>@endif
                        @if (canAccess('form-pengawas-batubara.index'))<li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-batubara.index') }}">Pengawas Batu Bara</a></li>@endif
                        @if (canAccess('pengawas-pitstop.index'))<li class="pc-item"><a class="pc-link" href="{{ route('pengawas-pitstop.index') }}">Pengawas Pitstop</a></li>@endif
                        @if (canAccess('laporan-kata-sandi.index'))<li class="pc-item"><a class="pc-link" href="{{ route('laporan-kata-sandi.index') }}">Laporan Kata Sandi</a></li>@endif
                    </ul>
                </li>
                @endif

                {{-- FORM SAP --}}
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
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/to-do-list.png" alt="DS"> </span><span class="pc-mtext">Form SAP</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span> <span class="pc-badge">8</span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('form-pengawas-sap.index'))<li class="pc-item"><a class="pc-link" href="{{ route('form-pengawas-sap.index') }}">Inspeksi</a></li>@endif
                        @if (canAccess('klkh.loading-point'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.loading-point') }}">KLKH Loading Point</a></li>@endif
                        @if (canAccess('klkh.haul-road'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.haul-road') }}">KLKH Haul Road</a></li>@endif
                        @if (canAccess('klkh.disposal'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.disposal') }}">KLKH Disposal/Dumping Point</a></li>@endif
                        @if (canAccess('klkh.lumpur'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.lumpur') }}">KLKH Dumping di Kolam Air/Lumpur</a></li>@endif
                        @if (canAccess('klkh.ogs'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.ogs') }}">KLKH OGS</a></li>@endif
                        @if (canAccess('klkh.batubara'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.batubara') }}">KLKH Batu Bara</a></li>@endif
                        @if (canAccess('klkh.simpangempat'))<li class="pc-item"><a class="pc-link" href="{{ route('klkh.simpangempat') }}">KLKH Intersection (Simpang Empat)</a></li>@endif
                    </ul>
                </li>
                @endif

                {{-- VERIFIKASI --}}
                @if (
                    canAccess('verifikasi.klkh') ||
                    canAccess('monitoring.p2h')
                )
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/stamp.png" alt="DS"> </span><span class="pc-mtext">Verifikasi</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('verifikasi.klkh'))<li class="pc-item"><a class="pc-link" href="{{ route('verifikasi.klkh') }}">KLKH</a></li>@endif
                        @if (canAccess('monitoring.p2h'))<li class="pc-item"><a class="pc-link" href="{{ route('monitoring.p2h') }}">P2H</a></li>@endif
                    </ul>
                </li>
                @endif

                {{-- P2H UNIT --}}
                @if (canAccess('p2h.index'))
                <li class="pc-item"><a href="{{ route('p2h.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/worker.png" alt="NT"></span><span class="pc-mtext">P2H Unit</span></a></li>
                @endif

                {{-- KKH --}}
                @if (canAccess('kkh.all') || canAccess('kkh.name'))
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link"><span class="pc-micon">
                        <img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/ergonomic.png" alt="DS"> </span><span class="pc-mtext">KKH Produksi</span> <span class="pc-arrow"><i data-feather="chevron-right"></i></span> <span class="pc-badge">2</span>
                    </a>
                    <ul class="pc-submenu">
                        @if (canAccess('kkh.all'))<li class="pc-item"><a class="pc-link" href="{{ route('kkh.all') }}">Seleksi per Tanggal</a></li>@endif
                        @if (canAccess('kkh.name'))<li class="pc-item"><a class="pc-link" href="{{ route('kkh.name') }}">Seleksi per Nama</a></li>@endif
                    </ul>
                </li>
                @endif

                {{-- JOB PENDING --}}
                @if (canAccess('jobpending'))
                <li class="pc-item"><a href="{{ route('jobpending') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/job-creation.png" alt="NT"></span><span class="pc-mtext">Job Pending</span></a></li>
                @endif

                {{-- ADMIN / MANAGER --}}
                @if (canAccess('rosterkerja'))
                <li class="pc-item"><a href="{{ route('rosterkerja') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/project.png" alt="NT"></span><span class="pc-mtext">Roster Kerja</span></a></li>
                @endif
                @if (canAccess('monitoringlaporankerjaklkh'))
                <li class="pc-item"><a href="{{ route('monitoringlaporankerjaklkh') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/spyware.png" alt="NT"></span><span class="pc-mtext">Monitoring LK & KLKH</span></a></li>
                @endif

                {{-- CONFIG --}}
                @if (canAccess('user.index') || canAccess('log.index'))
                <li class="pc-item pc-caption"><label>Configuration</label></li>
                @if (canAccess('user.index'))
                <li class="pc-item"><a href="{{ route('user.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/user.png" alt="NT"></span><span class="pc-mtext">Users</span></a></li>
                @endif
                @if (canAccess('log.index'))
                <li class="pc-item"><a href="{{ route('log.index') }}" class="pc-link"><span class="pc-micon"><img class="pc-icon" src="{{ asset('dashboard/assets') }}/images/widget/log.png" alt="NT"></span><span class="pc-mtext">Logging User</span></a></li>
                @endif
                @endif
            </ul>
        </div>
    </div>
</nav>
@include('layout.modal.change-password')
