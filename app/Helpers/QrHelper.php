<?php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('generateQrStorage')) {
    /**
     * Generate QR code PNG ke file storage dan kembalikan path publiknya.
     *
     * @param string $text   Teks yang akan di-encode ke QR
     * @param string $fileName Nama file PNG, misal 'qr_123.png'
     * @param int $size Ukuran QR code (default 150)
     * @return string URL publik file QR, misal 'storage/qr-temp/qr_123.png'
     */
    function generateQrStorage(string $text, string $fileName, int $size = 150): string
    {
        // Folder di storage/app/public/qr-temp
        $folder = storage_path('app/qr-temp');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filePath = $folder . DIRECTORY_SEPARATOR . $fileName;

        // Generate QR code PNG ke file
        QrCode::size($size)->format('png')->generate($text, $filePath);

        // Kembalikan path publik (sesuai syarat storage:link sudah dijalankan)
        return 'storage/qr-temp/' . $fileName;
    }
}
