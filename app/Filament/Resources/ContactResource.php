<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Contacts';

    protected static ?string $modelLabel = 'Contact';

    protected static ?string $pluralModelLabel = 'Contacts';

    protected static UnitEnum|string|null $navigationGroup = 'Church Management';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Information')
                    ->schema([
                        Select::make('church_id')
                            ->relationship('church', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('branch_id', null)),

                        Select::make('branch_id')
                            ->relationship('branch', 'name', fn ($query, callable $get) =>
                                $query->where('church_id', $get('church_id'))
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('person_id')
                            ->relationship('person', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('stage')
                            ->options([
                                'prospect' => 'Prospect',
                                'new_convert' => 'New Convert',
                                'believer' => 'Believer',
                                'member' => 'Member',
                            ])
                            ->required()
                            ->default('prospect'),
                    ])
                    ->columns(2),

                Section::make('Demographics')
                    ->schema([
                        Select::make('age_group')
                            ->options([
                                '0-12' => '0-12',
                                '13-17' => '13-17',
                                '18-25' => '18-25',
                                '26-35' => '26-35',
                                '36-50' => '36-50',
                                '51-65' => '51-65',
                                '65+' => '65+',
                            ])
                            ->searchable(),

                        Select::make('marital_status')
                            ->options([
                                'single' => 'Single',
                                'married' => 'Married',
                                'divorced' => 'Divorced',
                                'widowed' => 'Widowed',
                            ])
                            ->searchable(),

                        TextInput::make('occupation')
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Section::make('Source & Capture')
                    ->schema([
                        Select::make('contact_source')
                            ->options([
                                'church_service' => 'Church Service',
                                'event' => 'Event',
                                'referral' => 'Referral',
                                'website' => 'Website',
                                'social_media' => 'Social Media',
                                'outreach' => 'Outreach',
                                'other' => 'Other',
                            ])
                            ->searchable(),

                        Select::make('captured_by')
                            ->relationship('capturedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->default(auth()->id())
                            ->label('Captured By'),

                        DateTimePicker::make('captured_at')
                            ->label('Captured At')
                            ->default(now()),
                    ])
                    ->columns(3),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('person.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                TextColumn::make('church.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('branch.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stage')
                    ->badge()
                    ->colors([
                        'gray' => 'prospect',
                        'success' => 'member',
                        'warning' => 'new_convert',
                        'info' => 'believer',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('age_group')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('marital_status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('contact_source')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('capturedBy.name')
                    ->label('Captured By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('captured_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('church')
                    ->relationship('church', 'name'),
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('stage')
                    ->options([
                        'prospect' => 'Prospect',
                        'new_convert' => 'New Convert',
                        'believer' => 'Believer',
                        'member' => 'Member',
                    ]),
                Tables\Filters\SelectFilter::make('age_group')
                    ->options([
                        '0-12' => '0-12',
                        '13-17' => '13-17',
                        '18-25' => '18-25',
                        '26-35' => '26-35',
                        '36-50' => '36-50',
                        '51-65' => '51-65',
                        '65+' => '65+',
                    ]),
                Tables\Filters\SelectFilter::make('marital_status')
                    ->options([
                        'single' => 'Single',
                        'married' => 'Married',
                        'divorced' => 'Divorced',
                        'widowed' => 'Widowed',
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
            ->defaultSort('captured_at', 'desc');
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
