<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan Server - Hubungi Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 480px;
            padding: 32px;
            text-align: center;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .error-icon svg {
            width: 40px;
            height: 40px;
            color: #dc2626;
        }

        .error-title {
            color: #1e293b;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .error-description {
            color: #64748b;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .admin-contact {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .admin-contact p {
            color: #475569;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-size: 14px;
        }

        .button-primary {
            background: #2563eb;
            color: white;
        }

        .button-primary:hover {
            background: #1d4ed8;
        }

        .button-secondary {
            background: white;
            color: #2563eb;
            border: 1px solid #d1d5db;
        }

        .button-secondary:hover {
            background: #f9fafb;
        }

        .button svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }

        @media (max-width: 480px) {
            .error-container {
                padding: 24px;
            }

            .error-icon {
                width: 64px;
                height: 64px;
            }

            .error-icon svg {
                width: 32px;
                height: 32px;
            }

            .error-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>

        <h1 class="error-title">Oops, Terjadi Gangguan</h1>
        <p class="error-description">
            Telah terjadi kendala teknis sementara. Silakan hubungi tim support kami untuk bantuan lebih lanjut.
        </p>

        <div class="admin-contact">
            <p>Tim support siap membantu Anda. Hubungi kami untuk mengatasi gangguan segera.</p>
        </div>

        <div class="button-group">

            <button class="button button-secondary" onclick="handleGoHome()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </button>
        </div>
    </div>

    <script>

        function handleGoHome() {
            window.location.href = "http://ptsims.co.id";
        }
    </script>
</body>
</html>
