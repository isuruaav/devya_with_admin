<?php

namespace App\Filament\Resources\Advertisements;

use App\Models\Advertisement;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AdvertisementResource extends Resource
{
    protected static ?string $model = Advertisement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|UnitEnum|null $navigationGroup = 'ANNOUNCEMENTS';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Advertisements';

    protected static ?string $modelLabel = 'Advertisement';

    protected static ?string $pluralModelLabel = 'Advertisements';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Advertisement Details')
                    ->description('Upload an image or video to display on the public queue display.')
                    ->schema([

                        TextInput::make('title')
                            ->label('Advertisement Title')
                            ->placeholder('Example: AYU Health & Wellness')
                            ->maxLength(255),

                        FileUpload::make('media_path')
                            ->label('Advertisement Image / Video')
                            ->disk('public')
                            ->directory('advertisements')
                            ->required()
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'video/mp4',
                                'video/webm',
                                'video/ogg',
                                'video/quicktime',
                            ])
                            ->maxSize(51200)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active advertisements will appear on the queue display.'),

                        TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('0 = first, 1 = second, 2 = third...'),

                        TextInput::make('display_seconds')
                            ->label('Display Seconds')
                            ->numeric()
                            ->default(8)
                            ->minValue(3)
                            ->maxValue(120)
                            ->required()
                            ->helperText('How many seconds an image should be displayed.'),

                        DateTimePicker::make('starts_at')
                            ->label('Start From')
                            ->helperText('Leave empty to display immediately.'),

                        DateTimePicker::make('ends_at')
                            ->label('End At')
                            ->helperText('Leave empty to display without an end date.'),

                    ])
                    ->columns(2),

            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(
            [
                'super_admin',
                'admin',
                'reception',
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No title'),

                TextColumn::make('media_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'warning',
                        'image' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('media_path')
                    ->label('File')
                    ->formatStateUsing(
                        fn ($state) => basename((string) $state)
                    )
                    ->limit(35),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('display_seconds')
                    ->label('Seconds')
                    ->suffix(' sec')
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Start')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('End')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

            ])
            ->defaultSort('sort_order')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('No Advertisements')
            ->emptyStateDescription(
                'Upload your first image or video advertisement.'
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisements::route('/'),
            'create' => Pages\CreateAdvertisement::route('/create'),
            'edit' => Pages\EditAdvertisement::route('/{record}/edit'),
        ];
    }
}
