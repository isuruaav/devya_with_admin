<?php

namespace App\Filament\Resources\Consultations;

use App\Filament\Resources\Consultations\ConsultationResource\Pages\CreateConsultation;
use App\Filament\Resources\Consultations\ConsultationResource\Pages\EditConsultation;
use App\Filament\Resources\Consultations\ConsultationResource\Pages\ListConsultations;
use App\Filament\Resources\Consultations\ConsultationResource\Pages\ViewConsultation;
use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Models\Consultation;
use App\Models\Doctor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static string|\BackedEnum|null $navigationIcon =

        'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup =

        'CONSULTATIONS';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel =

        'Consultations';

    protected static ?string $modelLabel =

        'Consultation';

    protected static ?string $pluralModelLabel =

        'Consultations';

    /*

    |--------------------------------------------------------------------------

    | ACCESS

    |--------------------------------------------------------------------------

    */

    public static function canAccess(): bool
    {

        $user = Auth::user();

        if (! $user) {

            return false;

        }

        return $user->canAccessModule('consultations');

    }

    /*

    |--------------------------------------------------------------------------

    | LOGGED DOCTOR

    |--------------------------------------------------------------------------

    */

    protected static function loggedDoctor(): ?Doctor
    {

        return Auth::user()?->getActiveDoctor();

    }

    /*

    |--------------------------------------------------------------------------

    | NAVIGATION

    |--------------------------------------------------------------------------

    */

    public static function shouldRegisterNavigation(): bool
    {

        $user = Auth::user();

        if (! $user) {

            return false;

        }

        if (static::loggedDoctor()) {

            return false;

        }

        return static::canAccess();

    }

    /*

    |--------------------------------------------------------------------------

    | QUERY

    |--------------------------------------------------------------------------

    */

    public static function getEloquentQuery(): Builder
    {

        $query = parent::getEloquentQuery()

            ->with([

                'patient',

                'doctor',

                'treatments',

                'medicines',

            ]);

        $doctor = static::loggedDoctor();

        if ($doctor) {

            $query->where(

                'doctor_id',

                $doctor->id

            );

        }

        return $query;

    }

    /*

    |--------------------------------------------------------------------------

    | VALIDATE APPOINTMENT DATA

    |--------------------------------------------------------------------------

    */

    public static function validateAppointmentData(

        array $data,

        ?int $consultationId = null

    ): array {

        $patientType = $data['patient_type'] ?? null;

        if (! blank($patientType)) {

            $data['patient_type'] = strcasecmp(

                (string) $patientType,

                'Foreign'

            ) === 0

                ? 'Foreign'

                : 'Local';

        }

        $currency = $data['currency'] ?? null;

        if (blank($currency)) {

            $data['currency'] = strcasecmp(

                (string) ($data['patient_type'] ?? 'Local'),

                'Foreign'

            ) === 0

                ? 'USD'

                : 'LKR';

        } else {

            $data['currency'] = strtoupper((string) $currency);

        }

        $data['doctor_fee'] = (float) (

            $data['doctor_fee'] ?? 0

        );

        $data['treatment_total'] = (float) (

            $data['treatment_total'] ?? 0

        );

        $data['medicine_total'] = (float) (

            $data['medicine_total'] ?? 0

        );

        $data['grand_total'] =

            $data['doctor_fee']

            +

            $data['treatment_total'];

        return $data;

    }

    /*

    |--------------------------------------------------------------------------

    | FORM

    |--------------------------------------------------------------------------

    |

    | IMPORTANT:

    |

    | The complete consultation form is now handled only by:

    |

    | ConsultationForm::configure()

    |

    | There is intentionally NO duplicate patient_type Select here.

    |

    | This removes the old validation mismatch:

    |

    |   Local / Foreign

    |          vs

    |   local / foreign

    |

    |--------------------------------------------------------------------------

    */

    public static function form(

        Schema $schema

    ): Schema {

        return ConsultationForm::configure(

            $schema

        );

    }

    /*

    |--------------------------------------------------------------------------

    | TABLE

    |--------------------------------------------------------------------------

    */

    public static function table(

        Table $table

    ): Table {

        return $table

            ->columns([

                TextColumn::make(

                    'consultation_number'

                )

                    ->label(

                        'Consultation No'

                    )

                    ->searchable()

                    ->sortable()

                    ->weight(

                        'bold'

                    )

                    ->color(

                        'primary'

                    ),

                TextColumn::make(

                    'patient.full_name'

                )

                    ->label(

                        'Patient'

                    )

                    ->searchable()

                    ->sortable(),

                TextColumn::make(

                    'doctor.name'

                )

                    ->label(

                        'Doctor'

                    )

                    ->searchable()

                    ->sortable(),

                TextColumn::make(

                    'consultation_date'

                )

                    ->label(

                        'Date & Time'

                    )

                    ->dateTime(

                        'd M Y, h:i A'

                    )

                    ->sortable(),

                TextColumn::make(

                    'patient_type'

                )

                    ->label(

                        'Patient Type'

                    )

                    ->badge()

                    ->color(

                        fn ($state) => strtolower(

                            (string) $state

                        ) === 'foreign'

                                ? 'info'

                                : 'success'

                    )

                    ->formatStateUsing(

                        fn ($state) => strtolower(

                            (string) $state

                        ) === 'foreign'

                                ? 'Foreign'

                                : 'Local'

                    ),

                TextColumn::make(

                    'grand_total'

                )

                    ->label(

                        'Consultation Total'

                    )

                    ->money(

                        fn ($record) => $record->currency

                            ?? 'LKR'

                    )

                    ->sortable(),

                TextColumn::make(

                    'status'

                )

                    ->label(

                        'Status'

                    )

                    ->badge()

                    ->color(

                        fn ($state) => match (

                            strtolower(

                                (string) $state

                            )

                        ) {

                            'completed' => 'success',

                            'cancelled' => 'danger',

                            default => 'warning',

                        }

                    )

                    ->formatStateUsing(

                        fn ($state) => ucfirst(

                            (string) $state

                        )

                    ),

            ])

            /*

            |--------------------------------------------------------------------------

            | FILTERS

            |--------------------------------------------------------------------------

            */

            ->filters([

                SelectFilter::make(

                    'doctor_id'

                )

                    ->label(

                        'Doctor'

                    )

                    ->relationship(

                        'doctor',

                        'name'

                    )

                    ->searchable()

                    ->preload(),

                SelectFilter::make(

                    'status'

                )

                    ->label(

                        'Status'

                    )

                    ->options([

                        'pending' => 'Pending',

                        'completed' => 'Completed',

                        'cancelled' => 'Cancelled',

                    ]),

                SelectFilter::make(

                    'patient_type'

                )

                    ->label(

                        'Patient Type'

                    )

                    ->options([

                        'Local' => 'Local',

                        'Foreign' => 'Foreign',

                    ]),

            ])

            /*

            |--------------------------------------------------------------------------

            | ACTIONS

            |--------------------------------------------------------------------------

            */

            ->actions([

                Action::make('transfer_doctor')

                    ->label('Transfer to Doctor')

                    ->color('warning')

                    ->icon('heroicon-o-arrows-right-left')

                    ->form([

                        Select::make('doctor_id')

                            ->label('New Doctor')

                            ->options(

                                Doctor::query()

                                    ->where('is_active', true)

                                    ->orderBy('name')

                                    ->pluck('name', 'id')

                                    ->all()

                            )

                            ->searchable()

                            ->preload()

                            ->required(),

                    ])

                    ->action(function (Consultation $record, array $data): void {

                        $doctor = Doctor::query()

                            ->whereKey($data['doctor_id'])

                            ->first();

                        if (! $doctor) {

                            Notification::make()

                                ->title('Doctor not found')

                                ->body('Please select a valid active doctor.')

                                ->danger()

                                ->send();

                            return;

                        }

                        $record->transferToDoctor($doctor);

                        Notification::make()

                            ->title('Patient transferred')

                            ->body(

                                'This consultation and appointment were reassigned to '.$doctor->name.'.'

                            )

                            ->success()

                            ->send();

                    }),

                ViewAction::make(),

                EditAction::make(),

                DeleteAction::make(),

            ])

            /*

            |--------------------------------------------------------------------------

            | BULK ACTIONS

            |--------------------------------------------------------------------------

            */

            ->bulkActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ])

            /*

            |--------------------------------------------------------------------------

            | DEFAULT SORT

            |--------------------------------------------------------------------------

            */

            ->defaultSort(

                'consultation_date',

                'desc'

            );

    }

    /*

    |--------------------------------------------------------------------------

    | RELATIONS

    |--------------------------------------------------------------------------

    */

    public static function getRelations(): array
    {

        return [];

    }

    /*

    |--------------------------------------------------------------------------

    | PAGES

    |--------------------------------------------------------------------------

    */

    public static function getPages(): array
    {

        return [

            'index' => ListConsultations::route('/'),

            'create' => CreateConsultation::route('/create'),

            'view' => ViewConsultation::route(

                '/{record}'

            ),

            'edit' => EditConsultation::route(

                '/{record}/edit'

            ),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | ACCESS CONTROL

    |--------------------------------------------------------------------------

    */

    public static function canViewAny(): bool
    {

        return static::canAccess();

    }

    public static function canCreate(): bool
    {

        return static::canAccess();

    }

    public static function canEdit(

        $record

    ): bool {

        $doctor =

            static::loggedDoctor();

        if (! $doctor) {

            return static::canAccess();

        }

        return (int) $record->doctor_id

            === (int) $doctor->id;

    }

    public static function canDelete(

        $record

    ): bool {

        $doctor =

            static::loggedDoctor();

        if (! $doctor) {

            return static::canAccess();

        }

        return (int) $record->doctor_id

            === (int) $doctor->id;

    }

    public static function canView(

        $record

    ): bool {

        $doctor =

            static::loggedDoctor();

        if (! $doctor) {

            return static::canAccess();

        }

        return (int) $record->doctor_id

            === (int) $doctor->id;

    }
}
