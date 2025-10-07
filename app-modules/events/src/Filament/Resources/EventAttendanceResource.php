<?php

namespace ChurchPanel\Events\Filament\Resources;

use BackedEnum;
use ChurchPanel\Events\Filament\Resources\EventAttendanceResource\Pages;
use ChurchPanel\Events\Models\EventAttendance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EventAttendanceResource extends Resource
{
    protected static ?string $model = EventAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Event Attendance';

    protected static ?string $modelLabel = 'Attendance';

    protected static ?string $pluralModelLabel = 'Event Attendance';

    protected static UnitEnum|string|null $navigationGroup = 'Events Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event & Attendee Information')
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Event'),

                        MorphToSelect::make('attendanceable')
                            ->label('Attendee')
                            ->types([
                                MorphToSelect\Type::make(\ChurchPanel\People\Models\Person::class)
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                                    ->getSearchResultsUsing(function (string $search) {
                                        return \ChurchPanel\People\Models\Person::query()
                                            ->where(function ($query) use ($search) {
                                                $query->where('first_name', 'like', "%{$search}%")
                                                    ->orWhere('last_name', 'like', "%{$search}%")
                                                    ->orWhereRaw("first_name || ' ' || last_name like ?", ["%{$search}%"]);
                                            })
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(fn ($person) => [$person->id => $person->first_name . ' ' . $person->last_name])
                                            ->toArray();
                                    }),
                                MorphToSelect\Type::make(\ChurchPanel\People\Models\Contact::class)
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                                    ->getSearchResultsUsing(function (string $search) {
                                        return \ChurchPanel\People\Models\Contact::query()
                                            ->where(function ($query) use ($search) {
                                                $query->where('first_name', 'like', "%{$search}%")
                                                    ->orWhere('last_name', 'like', "%{$search}%")
                                                    ->orWhereRaw("first_name || ' ' || last_name like ?", ["%{$search}%"]);
                                            })
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(fn ($contact) => [$contact->id => $contact->first_name . ' ' . $contact->last_name])
                                            ->toArray();
                                    }),
                            ])
                            ->required()
                            ->searchable(),

                        Select::make('attendance_status')
                            ->options([
                                'present' => 'Present',
                                'absent' => 'Absent',
                                'late' => 'Late',
                                'excused' => 'Excused',
                            ])
                            ->default('present')
                            ->required(),

                        Select::make('check_in_method')
                            ->options([
                                'manual' => 'Manual',
                                'qr_code' => 'QR Code',
                                'nfc' => 'NFC',
                                'app' => 'Mobile App',
                            ])
                            ->default('manual')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Check-In Details')
                    ->schema([
                        DateTimePicker::make('check_in_time')
                            ->native(false)
                            ->seconds(false)
                            ->label('Check-In Time'),

                        DateTimePicker::make('check_out_time')
                            ->native(false)
                            ->seconds(false)
                            ->label('Check-Out Time')
                            ->after('check_in_time'),

                        Select::make('checked_in_by')
                            ->relationship('checkedInBy', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Checked In By'),
                    ])
                    ->columns(3),

                Section::make('First-Time Visitor Information')
                    ->schema([
                        Checkbox::make('first_time_visitor')
                            ->label('First Time Visitor')
                            ->default(false)
                            ->live(),

                        Select::make('brought_by')
                            ->relationship('broughtBy', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                            ->getSearchResultsUsing(function (string $search) {
                                return \ChurchPanel\People\Models\Person::query()
                                    ->where(function ($query) use ($search) {
                                        $query->where('first_name', 'like', "%{$search}%")
                                            ->orWhere('last_name', 'like', "%{$search}%")
                                            ->orWhereRaw("first_name || ' ' || last_name like ?", ["%{$search}%"]);
                                    })
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($person) => [$person->id => $person->first_name . ' ' . $person->last_name])
                                    ->toArray();
                            })
                            ->searchable()
                            ->label('Brought By (Member)')
                            ->helperText('Who invited or brought this visitor?')
                            ->visible(fn ($get) => $get('first_time_visitor')),
                    ])
                    ->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('attendanceable_type')
                    ->label('Attendee Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->colors([
                        'info' => fn ($state) => str_contains($state, 'Person'),
                        'warning' => fn ($state) => str_contains($state, 'Contact'),
                    ])
                    ->toggleable(),

                TextColumn::make('attendanceable.first_name')
                    ->label('Attendee')
                    ->formatStateUsing(function ($record) {
                        return $record->getAttendeeName();
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attendance_status')
                    ->badge()
                    ->colors([
                        'success' => 'present',
                        'danger' => 'absent',
                        'warning' => 'late',
                        'info' => 'excused',
                    ])
                    ->sortable(),

                TextColumn::make('check_in_time')
                    ->label('Check-In')
                    ->dateTime('M j, g:i A')
                    ->sortable(),

                TextColumn::make('check_out_time')
                    ->label('Check-Out')
                    ->dateTime('M j, g:i A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('check_in_method')
                    ->badge()
                    ->colors([
                        'gray' => 'manual',
                        'success' => 'qr_code',
                        'info' => 'nfc',
                        'primary' => 'app',
                    ])
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('first_time_visitor')
                    ->boolean()
                    ->label('First Timer')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('broughtBy.first_name')
                    ->label('Brought By')
                    ->formatStateUsing(function ($record) {
                        if ($record->broughtBy) {
                            return $record->broughtBy->first_name . ' ' . $record->broughtBy->last_name;
                        }
                        return null;
                    })
                    ->toggleable(),

                TextColumn::make('checkedInBy.name')
                    ->label('Checked In By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),
                Tables\Filters\SelectFilter::make('attendance_status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'excused' => 'Excused',
                    ]),
                Tables\Filters\SelectFilter::make('check_in_method')
                    ->options([
                        'manual' => 'Manual',
                        'qr_code' => 'QR Code',
                        'nfc' => 'NFC',
                        'app' => 'Mobile App',
                    ]),
                Tables\Filters\TernaryFilter::make('first_time_visitor')
                    ->label('First Time Visitor')
                    ->boolean()
                    ->trueLabel('First timers only')
                    ->falseLabel('Regular attendees only')
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('check_in_time', 'desc');
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
            'index' => Pages\ListEventAttendance::route('/'),
            'create' => Pages\CreateEventAttendance::route('/create'),
            'edit' => Pages\EditEventAttendance::route('/{record}/edit'),
        ];
    }
}
