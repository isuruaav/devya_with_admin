<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'doctor_id', 'is_active', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'web';

    public const ROLES = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'reception' => 'Reception',
        'opd' => 'OPD',
        'pharmacy' => 'Pharmacy',
        'salon' => 'Salon',
        'doctor' => 'Doctor',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function canManageUsers(): bool
    {
        return $this->can('users.view');
    }

    public function canAccessModule(string $module): bool
    {
        return $this->can('access.'.$module);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isActive()
            && $this->roles()->where('guard_name', 'web')->exists()
            && (! $this->hasRole('doctor') || $this->getActiveDoctor() !== null);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getActiveDoctor(): ?Doctor
    {
        if (! $this->hasRole('doctor')) {
            return null;
        }

        $query = Doctor::query()->where('is_active', true);

        return $this->doctor_id
            ? $query->whereKey($this->doctor_id)->first()
            : $query->where('email', $this->email)->first();
    }

    public static function availableRolesFor(?self $user): array
    {
        if ($user?->isSuperAdmin()) {
            return self::ROLES;
        }

        return collect(self::ROLES)
            ->except('super_admin')
            ->all();
    }

    public function canAccessPatient(Patient $patient): bool
    {
        if (! $this->canAccessModule('patients') && ! $this->canAccessModule('history')) {
            return false;
        }

        if (! $this->hasRole('doctor')) {
            return true;
        }

        $doctor = $this->getActiveDoctor();

        return $doctor !== null && $patient->consultations()->where('doctor_id', $doctor->id)->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }
}
