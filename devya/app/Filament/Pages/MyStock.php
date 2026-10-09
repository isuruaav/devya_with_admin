<?php

namespace App\Filament\Pages;

use App\Models\Medicine;
use Filament\Pages\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class MyStock extends Page
{
    use WithPagination;

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('stock') ?? false;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'My Stock';

    protected static ?string $title = 'Medicine Stock Overview';

    protected string $view = 'filament.pages.my-stock';

    public function getStockQuery(): Builder
    {
        return Medicine::query()
            ->withSum([
                'consultationMedicines as used_quantity' => fn (Builder $query) => $query
                    ->whereHas('consultation'),
            ], 'quantity')
            ->orderBy('name');
    }

    public function getStockPage(): LengthAwarePaginator
    {
        return $this->getStockQuery()->paginate(10, ['*'], 'stockPage');
    }

    public function getTotalMedicines(): int
    {
        return Medicine::query()->count();
    }

    public function getLowStockCount(): int
    {
        return Medicine::query()
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->count();
    }

    public function getTotalRemaining(): int
    {
        return (int) Medicine::query()->sum('stock_quantity');
    }

    public function getTotalUsed(): int
    {
        return (int) $this->getStockQuery()->get()->sum('used_quantity');
    }
}
