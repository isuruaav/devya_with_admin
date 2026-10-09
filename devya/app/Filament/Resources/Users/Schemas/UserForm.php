<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Doctor;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form user-form'])
            ->components([
                Section::make('User Account')
                    ->contained(false)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name', modifyQueryUsing: fn (Builder $query): Builder => $query
                                ->where('guard_name', 'web')
                                ->whereIn('name', array_keys(User::availableRolesFor(auth()->user()))))
                            ->getOptionLabelFromRecordUsing(fn (Role $record): string => User::ROLES[$record->name] ?? $record->name)
                            ->multiple()
                            ->minItems(1)
                            ->maxItems(1)
                            ->rules([
                                fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                                    $selected = array_unique((array) $value);
                                    $allowedCount = Role::query()->where('guard_name', 'web')
                                        ->whereIn('name', array_keys(User::availableRolesFor(auth()->user())))
                                        ->whereIn('id', $selected)->count();

                                    if ($allowedCount !== count($selected)) {
                                        $fail('The selected role is not available to your account.');
                                    }
                                },
                            ])
                            ->default(fn (): array => [Role::findByName('reception', 'web')->id])
                            ->preload()
                            ->live()
                            ->required(),
                        Select::make('doctor_id')
                            ->label('Linked Doctor')
                            ->options(fn (): array => Doctor::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->visible(fn ($get): bool => Role::query()->whereIn('id', $get('roles') ?? [])->where('name', 'doctor')->exists())
                            ->required(fn ($get): bool => Role::query()->whereIn('id', $get('roles') ?? [])->where('name', 'doctor')->exists()),
                        Toggle::make('is_active')
                            ->label('Account Enabled')
                            ->inline(false)
                            ->default(true)
                            ->disabled(fn (): bool => ! auth()->user()?->can('users.enable'))
                            ->dehydrated(fn (): bool => auth()->user()?->can('users.enable') ?? false),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable(false)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->minLength(8)
                            ->same('password_confirmation'),
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable(false)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }
}
