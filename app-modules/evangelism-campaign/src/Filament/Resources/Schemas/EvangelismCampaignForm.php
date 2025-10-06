<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EvangelismCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Campaign Information')
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

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Select::make('type')
                            ->options([
                                'outreach' => 'Outreach',
                                'crusade' => 'Crusade',
                                'visitation' => 'Visitation',
                                'door_to_door' => 'Door to Door',
                                'online' => 'Online',
                            ])
                            ->required()
                            ->default('outreach'),

                        Select::make('status')
                            ->options([
                                'planning' => 'Planning',
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('planning'),
                    ])
                    ->columns(2),

                Section::make('Location & Timeline')
                    ->schema([
                        TextInput::make('location')
                            ->maxLength(255),

                        DatePicker::make('start_date')
                            ->native(false),

                        DatePicker::make('end_date')
                            ->native(false)
                            ->after('start_date'),
                    ])
                    ->columns(3),

                Section::make('Goals & Coordination')
                    ->schema([
                        TextInput::make('target_souls')
                            ->numeric()
                            ->minValue(1)
                            ->label('Target Souls'),

                        Select::make('coordinator_id')
                            ->relationship('coordinator', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Coordinator'),

                        TextInput::make('budget')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                    ])
                    ->columns(3),

                Section::make('Team Members')
                    ->schema([
                        Select::make('teamMembers')
                            ->relationship('teamMembers', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->label('Select Team Members'),
                    ])
                    ->collapsible(),
            ]);
    }
}
