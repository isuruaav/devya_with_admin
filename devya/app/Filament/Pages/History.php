<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class History extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('history') ?? false;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'History';

    protected static ?string $title = 'History';

    protected string $view = 'filament.pages.history';
}
