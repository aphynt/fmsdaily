<!doctype html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title><!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/inter/inter.css" id="main-font-link">
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/phosphor/duotone/style.css">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/tabler-icons.min.css"><!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/fonts/material.css"><!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/css/style.css" id="main-style-link">
    <script src="{{ asset('dashboard') }}/assets/js/tech-stack.js"></script>
    <link rel="stylesheet" href="{{ asset('dashboard') }}/assets/css/style-preset.css">
</head><!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div><!-- [ Pre-loader ] End -->
    <!-- [ Main Content ] start -->
    <div class="maintenance-block">
        <div class="container">
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card construction-card">
                        <div class="card-body">
                            <div class="construction-image-block">
                                <div class="row justify-content-center">
                                    <div class="col-12 text-center">
                                        <img src="{{ asset('dashboard/assets') }}/images/logo-full.png"
                                            class="img-fluid"
                                            style="max-width: 400px; width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <br>
                                <div style="max-width: 500px; margin: 0 auto; background: #f9fbfc; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif; color: #34495e;">
                                    <h2 style="color: #2c3e50; margin-bottom: 20px;">✅ Verifikasi Berhasil</h2>
                                    <h6 style="margin: 0 0 10px 0;"><strong>Nama:</strong> <span style="color: #2980b9;text-transform: uppercase;">{{ $user->name }}</span></h6>
                                    <h6 style="margin: 0 0 10px 0;"><strong>NIK:</strong> <span style="color: #2980b9;text-transform: uppercase;">{{ $user->nik }}</span></h6>
                                    <h6 style="margin: 0 0 20px 0;"><strong>Jabatan:</strong> <span style="color: #2980b9;text-transform: uppercase;">{{ $user->role }}</span></h6>
                                    <p style="font-size: 0.9em; color: #7f8c8d;">Data telah tervalidasi dan dicatat secara resmi dalam sistem.</p>
                                    <button onclick="window.close();" style="margin-top: 20px; background-color: #e74c3c; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                        ❌ Tutup Halaman
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- [ sample-page ] end -->
        </div>
    </div><!-- [ Main Content ] end -->
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('dashboard') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('dashboard') }}/assets/js/plugins/feather.min.js"></script>
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

</body>
</html>
