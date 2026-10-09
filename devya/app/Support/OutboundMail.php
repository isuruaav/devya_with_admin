<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

class OutboundMail
{
    public static function isEnabled(): bool
    {
        $transport = config('mail.mailers.'.config('mail.default').'.transport');

        return (bool) config('mail.enabled') && in_array($transport, ['smtp', 'sendmail', 'ses', 'ses-v2', 'postmark', 'resend'], true);
    }

    public static function ensureEnabled(): void
    {
        if (! self::isEnabled()) {
            throw ValidationException::withMessages(['email' => 'Email delivery is not configured.']);
        }
    }
}
