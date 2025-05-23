
<!--
    Copyright (c) 2024 Ahmad Fadillah - IT SIMS.
    All rights reserved. Unauthorized duplication is prohibited.
    -->
<!doctype html>
<html lang="en">

<head>
    <title>{{ $title }} - {{ config('app.name') }}</title>
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
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/dropzone.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/inter/inter.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/phosphor/duotone/style.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/feather.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/fonts/material.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/style.css" id="main-style-link">
    <script src="{{ asset('dashboard/assets') }}/js/tech-stack.js"></script>
    <script src="{{ asset('dashboard/assets') }}/js/hak-akses.js"></script>
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/style-preset.css">
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/uikit.css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/style-spinner.css"> --}}
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/datepicker-bs5.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/assets') }}/css/plugins/notifier.css">
    {{-- SweetAlert --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.lordicon.com/lordicon-1.1.0.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    {{-- <div id="loading-overlay">
        <div class="spinner"></div>
    </div> --}}
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <div id="notifier" class="notifier-container">
            <span id="notification-message"></span>

        </div>
    @include('layout.alert.general')
    <style>
        hr {
            border: none;
            /* Menghapus border default */
            height: 2px;
            /* Menentukan ketebalan garis */
            background-color: #011b32;
            /* Menambahkan warna latar belakang */
            margin: 20px 0;
            /* Menambahkan jarak vertikal di atas dan bawah garis */
        }
        /* Menghapus tanda panah datatables */
        .dt-column-order {
            display: none;
        }
    </style>
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
