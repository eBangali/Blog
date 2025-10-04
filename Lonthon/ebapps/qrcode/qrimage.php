<?php
/*
 * PHP QR Code encoder - Updated for modern PHP versions
 * 
 * Image output of code using GD2
 * 
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 */

define('QR_IMAGE', true);

class QRimage {

    //----------------------------------------------------------------------
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint = false, $back_color = 0xFFFFFF, $fore_color = 0x000000) 
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame, $back_color, $fore_color);

        if ($filename === false) {
            header("Content-Type: image/png");
            imagepng($image);
        } else {
            imagepng($image, $filename);
            if ($saveandprint === true) {
                header("Content-Type: image/png");
                imagepng($image);
            }
        }

        imagedestroy($image);
    }

    //----------------------------------------------------------------------
    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $quality = 85) 
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false) {
            header("Content-Type: image/jpeg");
            imagejpeg($image, null, $quality);
        } else {
            imagejpeg($image, $filename, $quality);
        }

        imagedestroy($image);
    }

    //----------------------------------------------------------------------
    private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4, $back_color = 0xFFFFFF, $fore_color = 0x000000) 
    {
        $height = count($frame);
        $width = strlen($frame[0]);

        $imgWidth = $width + 2 * $outerFrame;
        $imgHeight = $height + 2 * $outerFrame;

        $baseImage = imagecreatetruecolor($imgWidth, $imgHeight);

        // Decompose the color codes into RGB components
        $foregroundColor = self::hexToRgb($fore_color);
        $backgroundColor = self::hexToRgb($back_color);

        // Allocate colors
        $col[0] = imagecolorallocate($baseImage, $backgroundColor['red'], $backgroundColor['green'], $backgroundColor['blue']);
        $col[1] = imagecolorallocate($baseImage, $foregroundColor['red'], $foregroundColor['green'], $foregroundColor['blue']);

        // Fill background
        imagefill($baseImage, 0, 0, $col[0]);

        // Draw QR code pixels
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($frame[$y][$x] === '1') {
                    imagesetpixel($baseImage, $x + $outerFrame, $y + $outerFrame, $col[1]);
                }
            }
        }

        // Create final image with the specified pixel size
        $targetImage = imagecreatetruecolor($imgWidth * $pixelPerPoint, $imgHeight * $pixelPerPoint);
        imagecopyresampled($targetImage, $baseImage, 0, 0, 0, 0, $imgWidth * $pixelPerPoint, $imgHeight * $pixelPerPoint, $imgWidth, $imgHeight);
        imagedestroy($baseImage);

        return $targetImage;
    }

    //----------------------------------------------------------------------
    // Convert hexadecimal color to RGB array
    private static function hexToRgb($hexColor) 
    {
        return [
            'red' => ($hexColor >> 16) & 0xFF,
            'green' => ($hexColor >> 8) & 0xFF,
            'blue' => $hexColor & 0xFF
        ];
    }
}
?>
