<?php

namespace Tests\Unit\Filament\Resources\Doctors\Schemas;

use App\Filament\Resources\Doctors\Schemas\DoctorForm;
use App\Models\Doctor;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use Tests\TestCase;

class DoctorFormTest extends TestCase
{
    public function test_weekly_schedule_starts_with_one_day_row(): void
    {
        $livewire = new class extends LivewireComponent implements HasSchemas
        {
            public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
            {
                return null;
            }

            public function getOldSchemaState(string $statePath): mixed
            {
                return null;
            }

            public function getSchemaComponent(
                string $key,
                bool $withHidden = false,
                array $skipComponentsChildContainersWhileSearching = []
            ): SchemaComponent|Action|ActionGroup|null {
                return null;
            }

            public function getSchema(string $name): ?Schema
            {
                return null;
            }

            public function currentlyValidatingSchema(?Schema $schema): void {}

            public function getDefaultTestingSchemaName(): ?string
            {
                return null;
            }
        };

        $schema = Schema::make($livewire)
            ->model(new Doctor);

        $schema = DoctorForm::configure($schema);
        $weeklySchedule = collect($schema->getFlatComponents())
            ->first(fn ($component): bool => $component instanceof Repeater
                && $component->getName() === 'weeklySchedules');

        $this->assertInstanceOf(Repeater::class, $weeklySchedule);
        $this->assertCount(1, $weeklySchedule->getDefaultState());
    }
}
