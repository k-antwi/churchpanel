<?php

namespace ChurchPanel\Events\Filament\Resources;

use BackedEnum;
use ChurchPanel\Events\Filament\Resources\EventResource\Pages;
use ChurchPanel\Events\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    protected static UnitEnum|string|null $navigationGroup = 'Events Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Branch'),

                        Select::make('service_type_id')
                            ->relationship('serviceType', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Service Type'),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('location')
                            ->maxLength(255),

                        Select::make('coordinator_id')
                            ->relationship('coordinator', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Coordinator'),
                    ])
                    ->columns(2),

                Section::make('Date & Time')
                    ->schema([
                        DateTimePicker::make('start_datetime')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->label('Start Date & Time'),

                        DateTimePicker::make('end_datetime')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->label('End Date & Time')
                            ->after('start_datetime'),

                        TextInput::make('expected_attendees')
                            ->numeric()
                            ->minValue(0)
                            ->label('Expected Attendees'),

                        Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('scheduled')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Recurrence Settings')
                    ->schema([
                        KeyValue::make('recurrence_pattern')
                            ->label('Recurrence Pattern')
                            ->helperText('Example: {"type": "weekly", "days": [0, 3], "interval": 1} for Sundays & Wednesdays')
                            ->keyLabel('Key')
                            ->valueLabel('Value'),

                        DatePicker::make('recurrence_ends_at')
                            ->native(false)
                            ->label('Recurrence Ends At')
                            ->helperText('Leave empty for no end date'),
                    ])
                    ->collapsed()
                    ->columns(1),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        KeyValue::make('settings')
                            ->label('Event Settings')
                            ->keyLabel('Setting')
                            ->valueLabel('Value'),
                    ])
                    ->collapsed()
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('serviceType.name')
                    ->label('Service Type')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                TextColumn::make('start_datetime')
                    ->label('Start')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),

                TextColumn::make('end_datetime')
                    ->label('End')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable()
                    ->limit(30),

                TextColumn::make('coordinator.name')
                    ->label('Coordinator')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('expected_attendees')
                    ->label('Expected')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('service_type')
                    ->relationship('serviceType', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_datetime', 'desc');
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
