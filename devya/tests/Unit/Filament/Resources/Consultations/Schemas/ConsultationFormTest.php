<?php

namespace Tests\Unit\Filament\Resources\Consultations\Schemas;

use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Models\Consultation;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use Tests\TestCase;

class ConsultationFormTest extends TestCase
{
    public function test_form_labels_clinical_notes_and_exposes_meal_dosage_inputs_and_legacy_directions(): void
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

        $schema = ConsultationForm::configure(
            Schema::make($livewire)->model(new Consultation)
        );
        $components = collect($schema->getFlatComponents());
        $clinicalNotes = $components->first(
            fn (SchemaComponent $component): bool => $component instanceof Field
                && $component->getName() === 'notes'
        );
        $medicines = $components->first(
            fn (SchemaComponent $component): bool => $component instanceof Repeater
                && $component->getName() === 'medicines'
        );

        $this->assertSame('Clinical Notes', $clinicalNotes?->getLabel());
        $this->assertInstanceOf(Repeater::class, $medicines);
        $this->assertSame(
            [
                'medicine_id',
                'breakfast_dose',
                'breakfast_timing',
                'lunch_dose',
                'lunch_timing',
                'dinner_dose',
                'dinner_timing',
                'duration_days',
                'quantity',
                'dosage',
            ],
            collect($medicines->getChildComponents())
                ->filter(fn (SchemaComponent $component): bool => $component instanceof Field)
                ->map(fn (Field $component): string => $component->getName())
                ->all()
        );
    }
}
