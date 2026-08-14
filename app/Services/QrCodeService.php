<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generate QR Code SVG/Image URL for a given table code or URL.
     */
    public static function getQrImageUrl(string $targetUrl, int $size = 250): string
    {
        $encodedUrl = urlencode($targetUrl);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedUrl}";
    }
}
