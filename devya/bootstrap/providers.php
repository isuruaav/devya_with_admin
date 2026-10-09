<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    PermissionServiceProvider::class,
    AppServiceProvider::class,
    AdminPanelProvider::class,
];
