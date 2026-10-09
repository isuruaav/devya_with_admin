<?php

namespace App\Filament\Resources\Staff\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Staff Documents';

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Document form.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Document Information')
                    ->description(
                        'Upload and manage confidential staff documents'
                    )
                    ->icon('heroicon-o-document-text')
                    ->schema([

                        Select::make('document_type')
                            ->label('Document Type')
                            ->options([
                                'NIC / Passport' => 'NIC / Passport',
                                'Birth Certificate' => 'Birth Certificate',
                                'GN Certificate' => 'GN Certificate',
                                'Appointment Letter' => 'Appointment Letter',
                                'Educational Certificate' => 'Educational Certificate',
                                'Professional Certificate' => 'Professional Certificate',
                                'Service Certificate' => 'Service Certificate',
                                'Medical Certificate' => 'Medical Certificate',
                                'Other' => 'Other',
                            ])
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->label('Document Title')
                            ->placeholder('e.g. NIC Copy - Front & Back')
                            ->required()
                            ->maxLength(255),

                        DatePicker::make('document_date')
                            ->label('Document Date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->nullable(),

                        FileUpload::make('file_path')
                            ->label('Upload Document')
                            ->disk('staff_documents')
                            ->directory('')
                            ->visibility('private')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->maxSize(10240)
                            ->storeFileNamesIn('original_file_name')
                            ->required(
                                fn (string $operation): bool => $operation === 'create'
                            )
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder(
                                'Additional information about this document...'
                            )
                            ->rows(3)
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        Select::make('is_current')
                            ->label('Document Status')
                            ->options([
                                true => 'Current',
                                false => 'Old / Previous',
                            ])
                            ->default(true)
                            ->required(),

                    ])
                    ->columns(2),
            ]);
    }

    /**
     * Documents table.
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('document_type')
                    ->label('Document Type')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('original_file_name')
                    ->label('File Name')
                    ->limit(35)
                    ->toggleable(),

                TextColumn::make('file_type')
                    ->label('File Type')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('file_size')
                    ->label('File Size')
                    ->formatStateUsing(
                        function ($state): string {
                            if (! $state) {
                                return 'Unknown';
                            }

                            if ($state < 1024) {
                                return $state.' B';
                            }

                            if ($state < 1024 * 1024) {
                                return round($state / 1024, 2).' KB';
                            }

                            return round(
                                $state / (1024 * 1024),
                                2
                            ).' MB';
                        }
                    )
                    ->toggleable(),

                TextColumn::make('document_date')
                    ->label('Document Date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('is_current')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (bool $state): string => $state ? 'Current' : 'Previous'
                    )
                    ->color(
                        fn (bool $state): string => $state ? 'success' : 'gray'
                    ),

                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->headerActions([
                CreateAction::make('addDocument')
                    ->label('Add File')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Add Staff Document')
                    ->modalSubmitActionLabel('Save Document'),
            ])

            ->recordActions([
                ViewAction::make()
                    ->modalHeading('Staff Document Details'),

                EditAction::make()
                    ->modalHeading('Edit Staff Document'),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->modalHeading('Delete Staff Document')
                    ->modalDescription(
                        'Are you sure you want to delete this document?'
                    ),
            ])

            ->toolbarActions([]);
    }
}
