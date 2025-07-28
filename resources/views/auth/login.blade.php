<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Daily Foreman</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Digitalisasi laporan foreman untuk efisiensi alur proses dan peningkatan akurasi data">
    <meta name="keywords" content="Laporan Harian Pengawas">
    <meta name="author" content="FMS - PT. SIMS JAYA KALTIM">
    <link rel="icon" href="{{ asset('dashboard/assets') }}/images/icon.png" type="image/x-icon">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #001831;
            --secondary-color: #2c7be5;
            --accent-color: #00d97e;
            --text-color: #2d3748;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--text-color);
            line-height: 1.6;
        }

        .auth-main {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fb 0%, #e6f0ff 100%);
        }

        .auth-wrapper {
            width: 100%;
            max-width: 460px;
        }

        .auth-form {
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--white);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .card-body {
            padding: 40px;
        }

        .text-center {
            text-align: center;
        }

        .logo-full {
            max-width: 100%;
            height: auto;
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .saprator {
            position: relative;
            margin: 25px 0;
            text-align: center;
        }

        .saprator span {
            display: inline-block;
            position: relative;
            padding: 0 12px;
            color: #6c757d;
            font-size: 14px;
        }

        .saprator span::before,
        .saprator span::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 60px;
            height: 1px;
            background-color: #e9ecef;
        }

        .saprator span::before {
            right: 100%;
        }

        .saprator span::after {
            left: 100%;
        }

        h4 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            line-height: 1.5;
            color: var(--text-color);
            background-color: var(--white);
            background-clip: padding-box;
            border: 1px solid #dfe7f1;
            border-radius: var(--border-radius);
            transition: var(--transition);
            height: 48px;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 123, 229, 0.25);
            outline: 0;
        }

        .mb-3 {
            margin-bottom: 20px !important;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            font-size: 14px;
        }

        .btn-primary {
            color: var(--white);
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            border: 1px solid transparent;
            line-height: 1.5;
        }

        .btn-primary:hover {
            background-color: #002851;
            border-color: #002851;
            transform: translateY(-2px);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 56, 97, 0.5);
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        .form-check-label {
            color: #6c757d;
            font-size: 14px;
        }

        .input-primary:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .d-grid {
            display: grid !important;
        }

        .mt-1 {
            margin-top: 4px !important;
        }

        .mt-4 {
            margin-top: 24px !important;
        }

        .notifier-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            display: none;
            box-shadow: var(--shadow);
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

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .card-body {
                padding: 30px 20px;
            }

            h4 {
                font-size: 1.3rem;
            }

            .form-control {
                padding: 10px 14px;
                height: 44px;
            }

            .btn-primary {
                padding: 10px 16px;
                font-size: 15px;
            }

            .notifier-container {
                top: 10px;
                right: 10px;
                font-size: 14px;
                padding: 10px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-form {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Floating label effect */
        .floating-label-group {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 15px;
            top: 12px;
            color: #6c757d;
            font-size: 15px;
            transition: var(--transition);
        }

        .form-control:focus ~ .floating-label,
        .form-control:not(:placeholder-shown) ~ .floating-label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            background-color: var(--white);
            padding: 0 5px;
            color: var(--secondary-color);
        }
    </style>
</head>

<body>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="javascript:void(0);">
                                <img src="{{ asset('dashboard/assets') }}/images/logo-full.png" alt="Company Logo" class="logo-full">
                            </a>
                        </div>
                        {{-- <div class="saprator my-3"><span></span></div> --}}
                        <h4 class="text-center f-w-500 mb-3">Login - Laporan Harian Pengawas</h4>

                        <!-- Login alerts would go here -->

                        <form action="{{ route('login.post') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="floatingInput" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="floatingInput" placeholder="Masukkan NIK Anda" name="nik" required>
                            </div>
                            <div class="mb-3">
                                <label for="floatingInput1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="floatingInput1" placeholder="Masukkan Password Anda" name="password" required>
                            </div>
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1" checked>
                                    <label class="form-check-label text-muted" for="customCheckc1">Ingat Saya!</label>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn-primary">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="notifier-container hide"></div>

    <script>
        // Example of how you might show the notifier
        function showNotifier(message, type = 'success') {
            const notifier = document.querySelector('.notifier-container');
            notifier.textContent = message;
            notifier.className = 'notifier-container show';
            notifier.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';

            setTimeout(() => {
                notifier.className = 'notifier-container hide';
            }, 5000);
        }

        // Example usage:
        // showNotifier('Login berhasil!', 'success');
        // showNotifier('NIK atau password salah', 'error');
    </script>
</body>

</html>
