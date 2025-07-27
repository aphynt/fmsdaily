
<!--
    Copyright (c) 2024 Ahmad Fadillah - IT SIMS.
    All rights reserved. Unauthorized duplication is prohibited.
    -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Daily Foreman</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#001831">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Digitalisasi laporan foreman untuk efisiensi alur proses dan peningkatan akurasi data">
    <meta name="keywords" content="Laporan Harian Pengawas">
    <meta name="author" content="FMS - PT. SIMS JAYA KALTIM">
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/style.css" id="main-style-link">
    <script src="{{ asset('dashboard/assets') }}/js/tech-stack.js"></script>
    <script src="{{ asset('dashboard/assets') }}/js/hak-akses.js"></script>
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/notifier.css">
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">

    @include('layout.alert.general')

    <style>
        .notifier-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background-color: #001831;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: opacity 0.5s ease-in-out;
            max-width: 90%;
        }

        .notifier-container.show {
            display: block;
            opacity: 1;
        }

        .notifier-container.hide {
            opacity: 0;
            display: block;
        }

        @media (max-width: 768px) {
            .notifier-container {
                top: 10px;
                right: 10px;
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>

    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">

                        <div class="text-center"><a href="javascript:void(0);"><img
                                    src="{{ asset('dashboard/assets') }}/images/logo-full.png" alt="img" width="250px"></a></div>
                        <div class="saprator my-3"><span></span></div>
                        <h4 class="text-center f-w-500 mb-3">Login - Laporan Harian Pengawas</h4>
                        @include('layout.alert.login')
                        <form action="{{ route('login.post') }}" method="post">
                            @csrf
                            <div class="mb-3"><input type="text" class="form-control" id="floatingInput" placeholder="NIK" name="nik"></div>
                            <div class="mb-3"><input type="password" class="form-control" id="floatingInput1" placeholder="Password" name="password"></div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                            <div class="form-check"><input class="form-check-input input-primary" type="checkbox" name="remember"
                                    id="customCheckc1" checked=""> <label class="form-check-label text-muted"
                                    for="customCheckc1">Ingat Saya!</label></div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary" style="width: 100%; font-size:14pt;">Masuk</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- [ Main Content ] end -->
    <script src="{{ asset('dashboard/assets') }}/js/pcoded.js"></script>

</body>

</html>
