<?php

namespace ChurchPanel\People\Filament\Resources;

use BackedEnum;
use ChurchPanel\People\Filament\Resources\WellbeingRecordResource\Pages;
use ChurchPanel\People\Models\Person;
use ChurchPanel\People\Models\WellbeingRecord;
use ChurchPanel\People\Models\Contact;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use UnitEnum;

class WellbeingRecordResource extends Resource
{
    protected static ?string $model = WellbeingRecord::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Wellbeing Records';

    protected static ?string $modelLabel = 'Wellbeing Record';

    protected static ?string $pluralModelLabel = 'Wellbeing Records';

    protected static UnitEnum|string|null $navigationGroup = 'People Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Record Information')
                    ->schema([
                        Select::make('recorded_by')
                            ->relationship('recordedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->default(auth()->id())
                            ->required()
                            ->label('Recorded By'),

                        DatePicker::make('record_date')
                            ->required()
                            ->default(now())
                            ->label('Record Date'),

                        Select::make('type')
                            ->options([
                                'spiritual' => 'Spiritual',
                                'physical' => 'Physical',
                                'financial' => 'Financial',
                                'emotional' => 'Emotional',
                            ])
                            ->required()
                            ->searchable(),

                        Select::make('status')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                                'critical' => 'Critical',
                            ])
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(2),

                Section::make('Person or Contact')
                    ->schema([
                        MorphToSelect::make('recordable')
                            ->label('Select Person or Contact')
                            ->types([
                                MorphToSelect\Type::make(Person::class)
                                    ->titleAttribute('first_name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name),
                                MorphToSelect\Type::make(Contact::class)
                                    ->titleAttribute('first_name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}"),
                            ])
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Details')
                    ->schema([
                        Textarea::make('prayer_requests')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Prayer Requests'),

                        Textarea::make('needs')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Needs'),

                        Textarea::make('assistance_provided')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Assistance Provided'),

                        Textarea::make('notes')
                            ->rows(4)
                            ->columnSpanFull()
                            ->label('Notes'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('record_date')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->label('Date'),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'spiritual',
                        'success' => 'physical',
                        'warning' => 'financial',
                        'info' => 'emotional',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'excellent',
                        'primary' => 'good',
                        'warning' => 'fair',
                        'danger' => 'poor',
                        'gray' => 'critical',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('recordable_type')
                    ->label('Type')
                    ->formatStateUsing(function ($state) {
                        return class_basename($state);
                    })
                    ->badge()
                    ->color(fn ($state) => str_contains($state, 'Person') ? 'success' : 'info')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('recordable')
                    ->label('Person/Contact')
                    ->formatStateUsing(function ($record) {
                        if (!$record->recordable) {
                            return '—';
                        }

                        if ($record->recordable instanceof Person) {
                            return $record->recordable->full_name;
                        }

                        return "{$record->recordable->first_name} {$record->recordable->last_name}";
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('recordedBy.name')
                    ->label('Recorded By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'spiritual' => 'Spiritual',
                        'physical' => 'Physical',
                        'financial' => 'Financial',
                        'emotional' => 'Emotional',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'fair' => 'Fair',
                        'poor' => 'Poor',
                        'critical' => 'Critical',
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
            ->defaultSort('record_date', 'desc');
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
            'index' => Pages\ListWellbeingRecords::route('/'),
            'create' => Pages\CreateWellbeingRecord::route('/create'),
            'edit' => Pages\EditWellbeingRecord::route('/{record}/edit'),
        ];
    }
}
