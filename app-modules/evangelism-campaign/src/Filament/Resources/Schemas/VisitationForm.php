<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VisitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Visitation Information')
                    ->schema([
                        Select::make('contact_id')
                            ->relationship('contact.person', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name . ' (' . $record->email . ')')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Contact'),

                        Select::make('visited_by')
                            ->relationship('visitor', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Visited By')
                            ->default(auth()->id()),

                        DatePicker::make('visit_date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->label('Visit Date'),

                        TextInput::make('purpose')
                            ->maxLength(255)
                            ->label('Purpose of Visit'),

                        TextInput::make('duration_minutes')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('minutes')
                            ->label('Duration'),

                        Select::make('attendance_status')
                            ->options([
                                'home' => 'Home',
                                'not_home' => 'Not Home',
                                'moved' => 'Moved',
                            ])
                            ->required()
                            ->default('home')
                            ->label('Attendance Status'),
                    ])
                    ->columns(2),

                Section::make('Visit Details')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Notes'),

                        Textarea::make('prayer_requests')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Prayer Requests'),

                        Textarea::make('needs_identified')
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Needs Identified')
                            ->hint('What needs did you identify during the visit?'),

                        Checkbox::make('follow_up_required')
                            ->label('Follow-up Required')
                            ->default(false),
                    ]),
            ]);
    }
}
