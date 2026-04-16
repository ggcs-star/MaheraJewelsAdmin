<?php

namespace App\Helpers;

class ColorHelper
{
    public static function getColorName(?string $hex): string
{
    if (!$hex) {
        return 'N/A';
    }

    $hex = strtoupper(trim($hex));

    static $colorMap = [

        '#000000' => 'Black',
        '#FFFFFF' => 'White',
        '#FF0000' => 'Red',
        '#00FF00' => 'Green',
        '#0000FF' => 'Blue',
        '#FFFF00' => 'Yellow',
        '#FF00FF' => 'Magenta',
        '#00FFFF' => 'Cyan',
        '#808080' => 'Gray',
        '#800000' => 'Maroon',
        '#808000' => 'Olive',
        '#008000' => 'Green',
        '#800080' => 'Purple',
        '#000080' => 'Navy',
        '#008080' => 'Teal',
        '#FFA500' => 'Orange',
        '#FFC0CB' => 'Pink',
        '#FFD700' => 'Gold',
        '#A52A2A' => 'Brown',
        '#4CAF50' => 'Green',
        '#2196F3' => 'Blue',
        '#F44336' => 'Red',
        '#9E9E9E' => 'Gray',
        '#607D8B' => 'Blue Gray',
        '#795548' => 'Brown',
        '#9C27B0' => 'Purple',
        '#673AB7' => 'Indigo',
        '#3F51B5' => 'Blue',
        '#03A9F4' => 'Light Blue',
        '#00BCD4' => 'Cyan',
        '#009688' => 'Teal',
        '#8BC34A' => 'Light Green',
        '#CDDC39' => 'Lime',
        '#FFC107' => 'Amber',
        '#FF9800' => 'Orange',
        '#FF5722' => 'Deep Orange'

    ];

    if (isset($colorMap[$hex])) {
        return $colorMap[$hex];
    }

    if (!preg_match('/^#[0-9A-F]{6}$/', $hex)) {
        return $hex;
    }

    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));

    $closestColor = '';
    $smallestDistance = PHP_INT_MAX;

    foreach ($colorMap as $colorHex => $name) {

        $cr = hexdec(substr($colorHex, 1, 2));
        $cg = hexdec(substr($colorHex, 3, 2));
        $cb = hexdec(substr($colorHex, 5, 2));

        $distance =
            pow($r - $cr, 2) +
            pow($g - $cg, 2) +
            pow($b - $cb, 2);

        if ($distance < $smallestDistance) {

            $smallestDistance = $distance;
            $closestColor = $name;
        }
    }

    return $closestColor ?: $hex;
}
}