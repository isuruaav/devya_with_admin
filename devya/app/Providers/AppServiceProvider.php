<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register before Spatie's permission callback so disabled accounts cannot be granted access.
        Gate::before(function (User $user, string $ability): ?bool {
            if (! $user->isActive()) {
                return false;
            }

            return $user->isSuperAdmin() ? true : null;
        });
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('public-appointments', fn (Request $request): array => [
            Limit::perMinute(5)->by('minute:'.$request->ip()),
            Limit::perHour(30)->by('hour:'.$request->ip()),
        ]);

        foreach (['confidential', 'staff_documents'] as $disk) {
            Storage::disk($disk)->buildTemporaryUrlsUsing(
                fn (string $path, $expiration, array $options): string => URL::temporarySignedRoute(
                    'documents.show', $expiration, ['path' => $path, 'disk' => $disk],
                ),
            );
        }
    }
}
