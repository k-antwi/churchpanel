<?php

namespace App\Filament\Resources\Churches;

use App\Filament\Resources\Churches\Pages;
use App\Models\Church;
use App\Models\User;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use UnitEnum;

class ChurchResource extends Resource
{
    protected static ?string $model = Church::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Churches';

    protected static ?string $modelLabel = 'Church';

    protected static ?string $pluralModelLabel = 'Churches';

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = 'Church Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        RichEditor::make('description')
                            ->columnSpanFull(),

                        Select::make('pastor_id')
                            ->label('Pastor')
                            ->relationship('pastor', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Section::make('Address')
                    ->schema([
                        Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('city')
                            ->maxLength(255),

                        TextInput::make('state')
                            ->maxLength(255),

                        TextInput::make('country')
                            ->maxLength(255),

                        TextInput::make('postal_code')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Location Coordinates')
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.00000001),

                        TextInput::make('longitude')
                            ->numeric()
                            ->step(0.00000001),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->maxSize(2048)
                            ->directory('churches/logos'),

                        FileUpload::make('cover_image')
                            ->image()
                            ->maxSize(5120)
                            ->directory('churches/covers'),
                    ])
                    ->columns(2),

                Section::make('Social Media')
                    ->schema([
                        KeyValue::make('social_media')
                            ->label('Social Media Links')
                            ->keyLabel('Platform')
                            ->valueLabel('URL')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('Service Times')
                    ->schema([
                        KeyValue::make('service_times')
                            ->label('Service Schedule')
                            ->keyLabel('Day/Service')
                            ->valueLabel('Time')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pastor.name')
                    ->label('Pastor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                Tables\Filters\SelectFilter::make('pastor')
                    ->relationship('pastor', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListChurches::route('/'),
            'create' => Pages\CreateChurch::route('/create'),
            'view' => Pages\ViewChurch::route('/{record}'),
            'edit' => Pages\EditChurch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
