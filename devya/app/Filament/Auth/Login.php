<?php

namespace App\Filament\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends \Filament\Auth\Pages\Login
{
    protected string $view = 'filament.auth.login';

    public function mount(): void
    {
        // Returning to the login entrance always requires fresh credentials.
        if (Filament::auth()->check()) {
            Filament::auth()->logout();
            session()->invalidate();
            session()->regenerateToken();
        }

        $this->form->fill();
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->hidden()
            ->dehydrated(false);
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'The email address or password is incorrect.',
        ]);
    }

    protected function isUserAllowedToAccessPanel(
        Authenticatable $user
    ): bool {
        if ($user instanceof User && ! $user->isActive()) {
            return false;
        }

        if (! ($user instanceof FilamentUser)) {
            return true;
        }

        return $user->canAccessPanel(
            Filament::getCurrentOrDefaultPanel()
        );
    }
}
