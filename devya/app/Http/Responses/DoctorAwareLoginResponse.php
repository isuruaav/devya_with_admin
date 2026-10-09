<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class DoctorAwareLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Pharmacy
        |--------------------------------------------------------------------------
        |
        | Pharmacy users should go directly to the Pharmacy Dashboard
        |
        */

        if (
            $user
            && $user->hasRole('pharmacy')
        ) {
            return redirect()->to(
                Filament::getUrl().'/pharmacy-dashboard'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor
        |--------------------------------------------------------------------------
        */

        if (
            $user
            && $user->hasRole('doctor')
        ) {
            return redirect()->to(
                Filament::getUrl()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin / Admin / Reception / Other Users
        |--------------------------------------------------------------------------
        */

        return redirect()->intended(
            Filament::getUrl()
        );
    }
}
