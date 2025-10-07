<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EvangelismCampaignTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('church.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('branch.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'outreach',
                        'success' => 'crusade',
                        'warning' => 'visitation',
                        'info' => 'door_to_door',
                        'danger' => 'online',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'planning',
                        'success' => 'active',
                        'info' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('target_souls')
                    ->numeric()
                    ->sortable()
                    ->toggleable()
                    ->label('Target'),

                TextColumn::make('reached_souls')
                    ->numeric()
                    ->sortable()
                    ->toggleable()
                    ->label('Reached')
                    ->getStateUsing(fn ($record) => $record->reached_souls),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(fn ($record) => $record->progress_percentage . '%'),

                TextColumn::make('capturedContacts_count')
                    ->label('Contacts Captured')
                    ->counts('capturedContacts')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('coordinator.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Coordinator'),

                TextColumn::make('budget')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('church')
                    ->relationship('church', 'name'),
                SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                SelectFilter::make('type')
                    ->options([
                        'outreach' => 'Outreach',
                        'crusade' => 'Crusade',
                        'visitation' => 'Visitation',
                        'door_to_door' => 'Door to Door',
                        'online' => 'Online',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'planning' => 'Planning',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
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
            ->defaultSort('start_date', 'desc');
    }
}
