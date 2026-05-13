<?php

namespace App\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

class QrPngService
{
    /**
     * Generate a QR code PNG using GD (no imagick required).
     * Returns the raw PNG binary string.
     */
    public static function generatePng(string $data, int $outputSize = 350): string
    {
        $qr     = Encoder::encode($data, ErrorCorrectionLevel::H());
        $matrix = $qr->getMatrix();

        $modules = $matrix->getWidth(); // square matrix
        $margin  = 2;                   // quiet zone in modules
        $total   = $modules + ($margin * 2);
        $scale   = (int) max(1, floor($outputSize / $total));
        $imgSize = $total * $scale;

        $img   = imagecreatetruecolor($imgSize, $imgSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        for ($y = 0; $y < $modules; $y++) {
            for ($x = 0; $x < $modules; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $px = ($x + $margin) * $scale;
                    $py = ($y + $margin) * $scale;
                    imagefilledrectangle($img, $px, $py, $px + $scale - 1, $py + $scale - 1, $black);
                }
            }
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return $png;
    }

    public static function generateBase64(string $data, int $outputSize = 350): string
    {
        return base64_encode(static::generatePng($data, $outputSize));
    }
}
