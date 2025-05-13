<header class="pc-header">
    <div class="header-wrapper">
        <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse"><a href="#" class="pc-head-link ms-0" id="sidebar-hide"><i
                            class="ti ti-menu-2"></i></a></li>
                <li class="pc-h-item pc-sidebar-popup"><a href="#" class="pc-head-link ms-0" id="mobile-collapse"><i
                            class="ti ti-menu-2"></i></a></li>
                <li class="pc-h-item  d-md-inline-flex">
                    {{-- <form class="form-search"><i class="search-icon"><svg class="pc-icon">
                                <use xlink:href="#custom-search-normal-1"></use>
                            </svg> </i><input type="search" class="form-control" placeholder="Ctrl + K"></form> --}}
                            <span class="badge bg-light-success rounded-pill ms-2 theme-version">{{ config('app.name') }}</span>
                </li>
            </ul>
        </div><!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                {{-- <li class="dropdown pc-h-item"><a class="pc-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                        aria-expanded="false"><svg class="pc-icon">
                            <use xlink:href="#custom-sun-1"></use>
                        </svg></a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown"><a href="#!" class="dropdown-item"
                            onclick="layout_change('dark')"><svg class="pc-icon">
                                <use xlink:href="#custom-moon"></use>
                            </svg> <span>Dark</span> </a><a href="#!" class="dropdown-item"
                            onclick="layout_change('light')"><svg class="pc-icon">
                                <use xlink:href="#custom-sun-1"></use>
                            </svg> <span>Light</span> </a><a href="#!" class="dropdown-item"
                            onclick="layout_change_default()"><svg class="pc-icon">
                                <use xlink:href="#custom-setting-2"></use>
                            </svg> <span>Default</span></a></div>
                </li> --}}

                {{-- <li class="dropdown pc-h-item"><a class="pc-head-link dropdown-toggle arrow-none me-0"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                        aria-expanded="false"><svg class="pc-icon">
                            <use xlink:href="#custom-notification"></use>
                        </svg> <span class="badge bg-success pc-h-badge">3</span></a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifications</h5><a href="#!" class="btn btn-link btn-sm">Tandai baca semua</a>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0"><svg class="pc-icon text-primary">
                                            <use xlink:href="#custom-layer"></use>
                                        </svg></div>
                                    <div class="flex-grow-1 ms-3"><span class="float-end text-sm text-muted">2
                                            min ago</span>
                                        <h5 class="text-body mb-2">Abdul Wahab</h5>
                                        <p class="mb-0">Telah mengisi catatan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center py-2"><a href="#!" class="link-danger">Bersihkan Notifikasi</a>
                        </div>
                    </div>
                </li> --}}
                <li class="dropdown pc-h-item header-user-profile"><a
                        class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false"><img
                            src="{{ asset('dashboard/assets') }}/images/user/avatar-1.png" alt="user-image" class="user-avtar"></a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Profile</h5>
                        </div>
                        <div class="dropdown-body">
                            <div class="profile-notification-scroll position-relative"
                                style="max-height: calc(100vh - 225px)">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0"><img src="{{ asset('dashboard/assets') }}/images/user/avatar-1.png"
                                            alt="user-image" class="user-avtar wid-35"></div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->name }}</h6><span><a href="#" style="color:#001932;">{{ Auth::user()->nik }}</a></span>
                                    </div>
                                </div>
                                <hr class="border-secondary border-opacity-50">
                                {{-- <div class="card">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0 d-inline-flex align-items-center"><svg
                                                    class="pc-icon text-muted me-2">
                                                    <use xlink:href="#custom-notification-outline"></use>
                                                </svg>Pemberitahuan</h5>
                                            <div class="form-check form-switch form-check-reverse m-0"><input
                                                    class="form-check-input f-18" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <p class="text-span">Manage</p><a href="#" onclick="Swal.fire('Oops!', 'Fitur ini belum berfungsi', 'warning'); return false;" class="dropdown-item"><span><svg
                                            class="pc-icon text-muted me-2">
                                            <use xlink:href="#custom-setting-outline"></use>
                                        </svg> <span>Profil</span> </span></a>
                                        <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePassword">
                                            <span> <svg class="pc-icon text-muted me-2"> <use xlink:href="#custom-share-bold"></use> </svg>
                                                <span>Ganti Password</span>
                                            </span>
                                        </a>

                                <hr class="border-secondary border-opacity-50">
                                <div class="pc-dark">
                                    <h6 class="mb-1">Tema</h6>
                                    <div class="row theme-color theme-layout">
                                        <div class="col-4">
                                            <div class="d-grid"><button class="preset-btn btn active" data-value="true"
                                                    onclick="layout_change('light');" data-bs-toggle="tooltip" title="Light"><svg
                                                        class="pc-icon text-warning">
                                                        <use xlink:href="#custom-sun-1"></use>
                                                    </svg></button></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-grid"><button class="preset-btn btn" data-value="false"
                                                    onclick="layout_change('dark');" data-bs-toggle="tooltip" title="Dark"><svg
                                                        class="pc-icon">
                                                        <use xlink:href="#custom-moon"></use>
                                                    </svg></button></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-grid"><button class="preset-btn btn" data-value="default"
                                                    onclick="layout_change_default();" data-bs-toggle="tooltip"
                                                    title="Automatically sets the theme based on user's operating system's color scheme."><span
                                                        class="pc-lay-icon d-flex align-items-center justify-content-center"><i
                                                            class="ph-duotone ph-cpu"></i></span></button></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-secondary border-opacity-50">
                                <div class="card-body">
                                    <div class="d-grid mb-12">
                                        <a href="{{ route('logout') }}" class="text-decoration-none">
                                            <span class="badge w-100" style="font-size:14px;background-color:#001932">
                                                <svg class="pc-icon me-2">
                                                    <use xlink:href="#custom-logout-1-outline"></use>
                                                </svg>
                                                Logout
                                            </span>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

