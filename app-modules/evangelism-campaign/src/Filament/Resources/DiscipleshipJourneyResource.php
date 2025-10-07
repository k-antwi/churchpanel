<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources;

use ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource\Pages\CreateDiscipleshipJourney;
use ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource\Pages\EditDiscipleshipJourney;
use ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource\Pages\ListDiscipleshipJourneys;
use ChurchPanel\EvangelismCampaign\Models\DiscipleshipJourney;
use App\Models\Contact;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class DiscipleshipJourneyResource extends Resource
{
    protected static ?string $model = DiscipleshipJourney::class;

    protected static ?string $slug = 'discipleship-journeys';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Discipleship Journeys';

    protected static ?string $modelLabel = 'Discipleship Journey';

    protected static ?string $pluralModelLabel = 'Discipleship Journeys';

    protected static UnitEnum|string|null $navigationGroup = 'Evangelism';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Journey Details')
                    ->schema([
                        Select::make('contact_id')
                            ->label('Contact')
                            ->options(function () {
                                return Contact::with('person')->get()->mapWithKeys(function ($contact) {
                                    return [$contact->id => $contact->person?->full_name ?? "Contact #{$contact->id}"];
                                });
                            })
                            ->searchable()
                            ->required(),

                        Select::make('stage')
                            ->options([
                                'new_convert' => 'New Convert',
                                'baptism_prep' => 'Baptism Preparation',
                                'baptized' => 'Baptized',
                                'foundation_class' => 'Foundation Class',
                                'membership_class' => 'Membership Class',
                                'maturity_class' => 'Maturity Class',
                                'leadership_training' => 'Leadership Training',
                                'serving' => 'Serving',
                            ])
                            ->required(),

                        Select::make('mentor_id')
                            ->label('Mentor')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('Timeline')
                    ->schema([
                        DateTimePicker::make('started_at')
                            ->label('Started At')
                            ->default(now()),

                        DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        Repeater::make('materials_given')
                            ->label('Materials Given')
                            ->schema([
                                TextInput::make('material')
                                    ->label('Material Name')
                                    ->required(),
                                DatePicker::make('date')
                                    ->label('Date Given')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contact.person.full_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable()
                    ->default('—'),

                TextColumn::make('stage')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'new_convert' => 'New Convert',
                        'baptism_prep' => 'Baptism Preparation',
                        'baptized' => 'Baptized',
                        'foundation_class' => 'Foundation Class',
                        'membership_class' => 'Membership Class',
                        'maturity_class' => 'Maturity Class',
                        'leadership_training' => 'Leadership Training',
                        'serving' => 'Serving',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'new_convert' => 'info',
                        'baptism_prep' => 'warning',
                        'baptized' => 'success',
                        'foundation_class', 'membership_class' => 'primary',
                        'maturity_class', 'leadership_training' => 'purple',
                        'serving' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mentor.name')
                    ->label('Mentor')
                    ->searchable()
                    ->sortable()
                    ->default('—'),

                TextColumn::make('started_at')
                    ->label('Started')
                    ->date()
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscipleshipJourneys::route('/'),
            'create' => CreateDiscipleshipJourney::route('/create'),
            'edit' => EditDiscipleshipJourney::route('/{record}/edit'),
        ];
    }
}
