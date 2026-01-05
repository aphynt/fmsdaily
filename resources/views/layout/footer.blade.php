<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row mt-3">
            <div class="col my-1">
                <p style="font-size: 8pt" class="m-0">Copyright &copy; by <a href="https://www.ptsims.co.id/"
                        target="_blank">IT-FMS</a></p>
            </div>
            <div class="col-auto my-1">
                <ul class="list-inline footer-link mb-0">
                    <li class="list-inline-item"><a href="http://poka.ptsims.co.id" target="_blank" style="font-size: 8pt">Portal Karyawan</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="pct-c-btn"><a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout"><i
            class="ph-duotone ph-gear-six"></i></a></div>
<div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Settings</h5><button type="button" class="btn btn-icon btn-link-danger ms-auto"
            data-bs-dismiss="offcanvas" aria-label="Close"><i class="ti ti-x"></i></button>
    </div>
    <div class="pct-body customizer-body">
        <div class="offcanvas-body py-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="pc-dark">
                        <h6 class="mb-1">Theme Mode</h6>
                        <p class="text-muted text-sm">Choose light or dark mode or Auto</p>
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
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Theme Contrast</h6>
                    <p class="text-muted text-sm">Choose theme contrast</p>
                    <div class="row theme-contrast">
                        <div class="col-6">
                            <div class="d-grid"><button class="preset-btn btn" data-value="true"
                                    onclick="layout_theme_contrast_change('true');" data-bs-toggle="tooltip"
                                    title="True"><svg class="pc-icon">
                                        <use xlink:href="#custom-mask"></use>
                                    </svg></button></div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid"><button class="preset-btn btn active" data-value="false"
                                    onclick="layout_theme_contrast_change('false');" data-bs-toggle="tooltip"
                                    title="False"><svg class="pc-icon">
                                        <use xlink:href="#custom-mask-1-outline"></use>
                                    </svg></button></div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Custom Theme</h6>
                    <p class="text-muted text-sm">Choose your primary theme color</p>
                    <div class="theme-color preset-color"><a href="#!" data-bs-toggle="tooltip" title="Blue"
                            class="active" data-value="preset-1"><i class="ti ti-checks"></i></a> <a href="#!"
                            data-bs-toggle="tooltip" title="Indigo" data-value="preset-2"><i
                                class="ti ti-checks"></i></a> <a href="#!" data-bs-toggle="tooltip" title="Purple"
                            data-value="preset-3"><i class="ti ti-checks"></i></a> <a href="#!" data-bs-toggle="tooltip"
                            title="Pink" data-value="preset-4"><i class="ti ti-checks"></i></a> <a href="#!"
                            data-bs-toggle="tooltip" title="Red" data-value="preset-5"><i class="ti ti-checks"></i></a>
                        <a href="#!" data-bs-toggle="tooltip" title="Orange" data-value="preset-6"><i
                                class="ti ti-checks"></i></a> <a href="#!" data-bs-toggle="tooltip" title="Yellow"
                            data-value="preset-7"><i class="ti ti-checks"></i></a> <a href="#!" data-bs-toggle="tooltip"
                            title="Green" data-value="preset-8"><i class="ti ti-checks"></i></a> <a href="#!"
                            data-bs-toggle="tooltip" title="Teal" data-value="preset-9"><i class="ti ti-checks"></i></a>
                        <a href="#!" data-bs-toggle="tooltip" title="Cyan" data-value="preset-10"><i
                                class="ti ti-checks"></i></a></div>
                </li>
                <li class="list-group-item">
                    <h6 class="mb-1">Theme layout</h6>
                    <p class="text-muted text-sm">Choose your layout</p>
                    <div class="theme-main-layout d-flex align-center gap-1 w-100">
                        <a href="#!" data-bs-toggle="tooltip" title="Vertical" class="active" data-value="vertical">
                            <img src="{{ asset('dashboard/assets/images/customizer/caption-on.svg') }}?v={{ config('app.asset_version') }}"
                                alt="img" class="img-fluid">
                        </a>
                        <a href="#!" data-bs-toggle="tooltip" title="Horizontal" data-value="horizontal">
                            <img src="{{ asset('dashboard/assets/images/customizer/horizontal.svg') }}?v={{ config('app.asset_version') }}"
                                alt="img" class="img-fluid">
                        </a>
                        <a href="#!" data-bs-toggle="tooltip" title="Color Header" data-value="color-header">
                            <img src="{{ asset('dashboard/assets/images/customizer/color-header.svg') }}?v={{ config('app.asset_version') }}"
                                alt="img" class="img-fluid">
                        </a>
                        <a href="#!" data-bs-toggle="tooltip" title="Compact" data-value="compact">
                            <img src="{{ asset('dashboard/assets/images/customizer/compact.svg') }}?v={{ config('app.asset_version') }}"
                                alt="img" class="img-fluid">
                        </a>
                        <a href="#!" data-bs-toggle="tooltip" title="Tab" data-value="tab">
                            <img src="{{ asset('dashboard/assets/images/customizer/tab.svg') }}?v={{ config('app.asset_version') }}"
                                alt="img" class="img-fluid">
                        </a>
                    </div>
                </li>

                <li class="list-group-item">
                    <h6 class="mb-1">Sidebar Caption</h6>
                    <p class="text-muted text-sm">Sidebar Caption Hide/Show</p>
                    <div class="row theme-color theme-nav-caption">
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn-img btn active" data-value="true"
                                        onclick="layout_caption_change('true');" data-bs-toggle="tooltip" title="Caption Show">
                                    <img src="{{ asset('dashboard/assets/images/customizer/caption-on.svg') }}?v={{ config('app.asset_version') }}"
                                        alt="img" class="img-fluid">
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button class="preset-btn btn-img btn" data-value="false"
                                        onclick="layout_caption_change('false');" data-bs-toggle="tooltip" title="Caption Hide">
                                    <img src="{{ asset('dashboard/assets/images/customizer/caption-off.svg') }}?v={{ config('app.asset_version') }}"
                                        alt="img" class="img-fluid">
                                </button>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="pc-rtl">
                        <h6 class="mb-1">Theme Layout</h6>
                        <p class="text-muted text-sm">LTR/RTL</p>
                        <div class="row theme-color theme-direction">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn active" data-value="false"
                                            onclick="layout_rtl_change('false');" data-bs-toggle="tooltip" title="LTR">
                                        <img src="{{ asset('dashboard/assets/images/customizer/ltr.svg') }}?v={{ config('app.asset_version') }}"
                                            alt="img" class="img-fluid">
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn" data-value="true"
                                            onclick="layout_rtl_change('true');" data-bs-toggle="tooltip" title="RTL">
                                        <img src="{{ asset('dashboard/assets/images/customizer/rtl.svg') }}?v={{ config('app.asset_version') }}"
                                            alt="img" class="img-fluid">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item pc-box-width">
                    <div class="pc-container-width">
                        <h6 class="mb-1">Layout Width</h6>
                        <p class="text-muted text-sm">Choose Full or Container Layout</p>
                        <div class="row theme-color theme-container">
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn active" data-value="false"
                                            onclick="change_box_container('false')" data-bs-toggle="tooltip" title="Full Width">
                                        <img src="{{ asset('dashboard/assets/images/customizer/full.svg') }}?v={{ config('app.asset_version') }}"
                                            alt="img" class="img-fluid">
                                    </button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn-img btn" data-value="true"
                                            onclick="change_box_container('true')" data-bs-toggle="tooltip" title="Fixed Width">
                                        <img src="{{ asset('dashboard/assets/images/customizer/fixed.svg') }}?v={{ config('app.asset_version') }}"
                                            alt="img" class="img-fluid">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="d-grid"><button class="btn btn-light-danger" id="layoutreset">Reset Layout</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
{{-- <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}
{{-- <script src="{{ asset('dashboard/assets') }}/js/plugins/apexcharts.min.js"></script> --}}
{{-- <script src="{{ asset('dashboard/assets') }}/js/pages/dashboard-default.js"></script> --}}
<!-- Required Js -->
<script src="{{ asset('dashboard/assets/js/plugins/popper.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/simplebar.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/bootstrap.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/fonts/custom-font.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/pcoded.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/feather.min.js') }}?v={{ config('app.asset_version') }}"></script>

<script src="{{ asset('dashboard/assets') }}/cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="{{ asset('dashboard/assets/js/plugins/dataTables.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/dataTables.bootstrap5.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/buttons.colVis.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/buttons.print.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/jszip.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/dataTables.buttons.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/vfs_fonts.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/buttons.html5.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/buttons.bootstrap5.min.js') }}?v={{ config('app.asset_version') }}"></script>

<script src="{{ asset('dashboard/assets/js/plugins/datepicker-full.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/flatpickr.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/choices.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/dropzone-amd-module.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/clipboard.min.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/component.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/notifier.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('imageCompress/browser-image-compression.js') }}?v={{ config('app.asset_version') }}"></script>
<script src="{{ asset('dashboard/assets/js/plugins/wizard.min.js') }}?v={{ config('app.asset_version') }}"></script>

    {{-- <script src="{{ asset('dashboard/assets') }}/js/pages/ac-notification.js"></script> --}}
<script>
 var timepicker1 = document.querySelector('#pc-timepicker-1');
    var timepicker2 = document.querySelector('#pc-timepicker-2');
    if(timepicker1){
        timepicker1.flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: true
        });
    }
    if(timepicker2){
        timepicker2.flatpickr({
            enableTime: true,
            noCalendar: true,
            time_24hr: true
        });
    }

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'Ketik nama disini'
            });
        }
    });

</script>
<script>
    // [ base style ]
    $('#base-style').DataTable();

    // [ no style ]
    $('#no-style').DataTable();

    // [ compact style ]
    $('#compact').DataTable();

    // [ hover style ]
    $('#table-style-hover').DataTable();

</script>
<script>
    layout_change('light');

</script>
<script>
    change_box_container('false');

</script>
<script>
    layout_caption_change('true');

</script>
<script>
    layout_rtl_change('false');

</script>
<script>
    preset_change('preset-1');

</script>
<script>
    main_layout_change('vertical');

</script>
<script>
   (function () {
        const element1 = document.querySelector('#pc-datepicker-1');
        if (element1) {
            const d_week = new Datepicker(element1, {
                buttonClass: 'btn',
                autohide: true,
            });
        }
    })();

    (function () {
        const element2 = document.querySelector('#pc-datepicker-2');
        if (element2) {
            const d_week = new Datepicker(element2, {
                buttonClass: 'btn',
                autohide: true,
            });
        }
})();

</script>
{{-- <script src="{{ asset('dashboard/assets') }}/js/plugins/wizard.min.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const wizardElement = document.querySelector('#basicwizard');
        if (wizardElement) {
            new Wizard(wizardElement, {
                validate: true,
                progress: true
            });
        }
});

    </script>
<script>
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue;
    }

    function formatDateToMMDDYYYY(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${month}/${day}/${year}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
            const currentDate = new Date();
            const today = formatDateToMMDDYYYY(currentDate);

            // Mengambil parameter 'rangeStart' dan 'rangeEnd' dari URL, dengan default ke tanggal hari ini
            const startDate = getQueryParam('rangeStart', today);
            const endDate = getQueryParam('rangeEnd', today);

            // Mencari elemen input dengan id 'range-start' dan 'range-end'
            const rangeStartInput = document.getElementById('range-start');
            const rangeEndInput = document.getElementById('range-end');

            // Menetapkan nilai pada elemen input jika ditemukan
            if (rangeStartInput) {
                rangeStartInput.value = startDate;
            }

            if (rangeEndInput) {
                rangeEndInput.value = endDate;
            }
        });
</script>
</body>

</html>
