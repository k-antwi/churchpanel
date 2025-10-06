<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FollowUpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Follow-up Information')
                    ->schema([
                        Select::make('contact_id')
                            ->relationship('contact.person', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name . ' (' . $record->email . ')')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Contact'),

                        Select::make('evangelism_campaign_id')
                            ->relationship('evangelismCampaign', 'title')
                            ->searchable()
                            ->preload()
                            ->label('Campaign'),

                        Select::make('assigned_to')
                            ->relationship('assignedTo', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Assigned To'),

                        Select::make('type')
                            ->options([
                                'phone' => 'Phone',
                                'sms' => 'SMS',
                                'email' => 'Email',
                                'visit' => 'Visit',
                            ])
                            ->required()
                            ->default('phone'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),

                        DateTimePicker::make('scheduled_date')
                            ->label('Scheduled Date')
                            ->native(false),

                        DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->native(false)
                            ->disabled(fn ($get) => $get('status') !== 'completed'),
                    ])
                    ->columns(2),

                Section::make('Details')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('outcome')
                            ->rows(3)
                            ->columnSpanFull()
                            ->hint('What was the result of this follow-up?'),

                        Textarea::make('next_action')
                            ->rows(3)
                            ->columnSpanFull()
                            ->hint('What should happen next?'),
                    ]),
            ]);
    }
}
