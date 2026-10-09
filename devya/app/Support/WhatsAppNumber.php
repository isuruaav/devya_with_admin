<?php

namespace App\Support;

class WhatsAppNumber
{
    public static function normalize(?string $phoneNumber): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phoneNumber) ?? '';

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 10 && str_starts_with($digits, '0')) {
            $digits = '94'.substr($digits, 1);
        } elseif (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            $digits = '94'.$digits;
        }

        if (strlen($digits) < 8 || strlen($digits) > 15) {
            return null;
        }

        return $digits;
    }
}
