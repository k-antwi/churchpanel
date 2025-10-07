<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\EvangelismCampaignResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CapturedContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'capturedContacts';

    protected static ?string $title = 'Captured Contacts';

    protected static ?string $recordTitleAttribute = 'first_name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stage')
                    ->badge()
                    ->colors([
                        'gray' => 'prospect',
                        'success' => 'member',
                        'warning' => 'new_convert',
                        'info' => 'believer',
                        'purple' => 'graduated',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('capturedBy.name')
                    ->label('Captured By')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('captured_at')
                    ->label('Captured At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('captured_at', 'desc');
    }
}
