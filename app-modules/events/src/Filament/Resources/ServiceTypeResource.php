<?php

namespace ChurchPanel\Events\Filament\Resources;

use BackedEnum;
use ChurchPanel\Events\Filament\Resources\ServiceTypeResource\Pages;
use ChurchPanel\Events\Models\ServiceType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceTypeResource extends Resource
{
    protected static ?string $model = ServiceType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Service Types';

    protected static ?string $modelLabel = 'Service Type';

    protected static ?string $pluralModelLabel = 'Service Types';

    protected static UnitEnum|string|null $navigationGroup = 'Events Management';

    protected static ?int $navigationSort = 1;

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

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from name'),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Description'),
                    ])
                    ->columns(2),

                Section::make('Display Settings')
                    ->schema([
                        ColorPicker::make('color')
                            ->default('#3B82F6')
                            ->required()
                            ->helperText('Color used for displaying this service type'),

                        TextInput::make('icon')
                            ->default('heroicon-o-calendar')
                            ->required()
                            ->helperText('Heroicon name (e.g., heroicon-o-calendar)'),

                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Order in which this type appears (lower numbers first)'),
                    ])
                    ->columns(3),

                Section::make('Service Configuration')
                    ->schema([
                        TextInput::make('default_duration_minutes')
                            ->numeric()
                            ->default(90)
                            ->required()
                            ->suffix('minutes')
                            ->helperText('Default duration for services of this type'),

                        Checkbox::make('is_active')
                            ->default(true)
                            ->label('Active')
                            ->helperText('Only active service types can be used for new events'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('branch.church.name')
                    ->label('Church')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                ColorColumn::make('color')
                    ->label('Color')
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Icon')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('default_duration_minutes')
                    ->label('Duration')
                    ->suffix(' min')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('display_order')
                    ->label('Order')
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
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
            ->defaultSort('display_order');
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
            'index' => Pages\ListServiceTypes::route('/'),
            'create' => Pages\CreateServiceType::route('/create'),
            'edit' => Pages\EditServiceType::route('/{record}/edit'),
        ];
    }
}
