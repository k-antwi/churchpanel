<?php

namespace ChurchPanel\People\Filament\Resources\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        Select::make('title')
                            ->options([
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Miss' => 'Miss',
                                'Ms' => 'Ms',
                                'Dr' => 'Dr',
                                'Rev' => 'Rev',
                                'Pastor' => 'Pastor',
                            ])
                            ->searchable(),

                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        DatePicker::make('date_of_birth')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),

                        Select::make('church_id')
                            ->relationship('church', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('type')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('mobile_phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('address_line')
                            ->maxLength(255),

                        TextInput::make('town')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(255),

                        TextInput::make('county')
                            ->maxLength(255),

                        TextInput::make('postcode')
                            ->maxLength(255),

                        TextInput::make('country')
                            ->maxLength(255),

                        TextInput::make('map_url')
                            ->url()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('bio')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('site')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('last_ip')
                            ->label('Last IP Address')
                            ->maxLength(255)
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }
}
