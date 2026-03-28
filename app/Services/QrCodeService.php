<?php

namespace App\Services;

use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate QR code untuk peminjaman
     */
    public static function generateQrCode($id_peminjaman)
    {
        // Generate unique QR code string
        $qrCode = 'PEMINJAMAN-' . strtoupper(Str::random(12)) . '-' . $id_peminjaman;
        
        return $qrCode;
    }

    /**
     * Verify QR code string
     */
    public static function verifyQrCode($qrCode)
    {
        // Format: PEMINJAMAN-XXXXXXXXXXXXX-ID_PEMINJAMAN
        if (!preg_match('/^PEMINJAMAN-([A-Z0-9]+)-(\d+)$/', $qrCode, $matches)) {
            return null;
        }

        return intval($matches[2]); // Return id_peminjaman
    }

    /**
     * Generate QR code image menggunakan Google Chart API
     * 
     * @param string $qrCode QR code string
     * @return string URL ke QR code image
     */
    public static function generateQrImage($qrCode)
    {
        try {
            $encoded = urlencode($qrCode);
            // Google Charts API untuk generate QR code
            return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$encoded}";
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate QR code image base64 menggunakan Google API
     * 
     * @param string $qrCode QR code string
     * @return string base64 encoded image
     */
    public static function generateQrImageBase64($qrCode)
    {
        try {
            $encoded = urlencode($qrCode);
            $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$encoded}";
            
            // Fetch image dari API
            $imageData = @file_get_contents($url);
            
            if ($imageData === false) {
                return null;
            }
            
            // Convert ke base64
            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            return null;
        }
    }
}
