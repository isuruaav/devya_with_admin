<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\QueueManagement;
use App\Http\Responses\DoctorAwareLoginResponse;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /*
    |--------------------------------------------------------------------------
    | Check whether logged-in user is a Doctor
    |--------------------------------------------------------------------------
    */

    protected function isDoctorUser(): bool
    {
        return auth()->user()?->getActiveDoctor() !== null;
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()

            /*
            |--------------------------------------------------------------------------
            | Panel
            |--------------------------------------------------------------------------
            */

            ->id('admin')
            ->path('admin')

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            ->login(Login::class)
            ->revealablePasswords(false)

            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */

            ->databaseNotifications()

            /*
            |--------------------------------------------------------------------------
            | Theme
            |--------------------------------------------------------------------------
            */

            ->colors([
                'primary' => Color::Amber,
            ])

            ->darkMode()
            ->themeSwitcher()
            ->defaultThemeMode(ThemeMode::System)

            ->viteTheme(
                'resources/css/filament/admin/theme.css'
            )

            /*
             |--------------------------------------------------------------------------
             | Sidebar
             |--------------------------------------------------------------------------
             */

            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('17rem')
            ->maxContentWidth('full')

            /*
             |--------------------------------------------------------------------------
             | Doctor-aware Login Response
             |--------------------------------------------------------------------------
             */

            ->bootUsing(function (): void {
                app()->bind(
                    LoginResponse::class,
                    DoctorAwareLoginResponse::class
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Navigation
            |--------------------------------------------------------------------------
            |
            | Queue Management now uses ONE page only.
            | Individual room queue links are removed from sidebar.
            |
            */

            ->navigationItems([])

            /*
            |--------------------------------------------------------------------------
            | Discover Resources
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            /*
            |--------------------------------------------------------------------------
            | Discover Pages
            |--------------------------------------------------------------------------
            */

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            /*
            |--------------------------------------------------------------------------
            | Dashboard + Custom Pages
            |--------------------------------------------------------------------------
            */

            ->pages([
                Dashboard::class,
                QueueManagement::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Discover Widgets
            |--------------------------------------------------------------------------
            */

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            /*
            |--------------------------------------------------------------------------
            | Widgets
            |--------------------------------------------------------------------------
            */

            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Middleware
            |--------------------------------------------------------------------------
            */

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | Authentication Middleware
            |--------------------------------------------------------------------------
            */

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
