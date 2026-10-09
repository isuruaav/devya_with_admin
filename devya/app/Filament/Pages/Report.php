<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Report extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('reports') ?? false;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Reports';

    protected string $view = 'filament.pages.report';
}
