<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FollowUpTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contact.person.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->label('Contact'),

                TextColumn::make('evangelismCampaign.title')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Campaign'),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'phone',
                        'success' => 'sms',
                        'warning' => 'email',
                        'info' => 'visit',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('scheduled_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Scheduled'),

                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->label('Completed'),

                TextColumn::make('assignedTo.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Assigned To'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('evangelism_campaign')
                    ->relationship('evangelismCampaign', 'title')
                    ->label('Campaign'),
                SelectFilter::make('type')
                    ->options([
                        'phone' => 'Phone',
                        'sms' => 'SMS',
                        'email' => 'Email',
                        'visit' => 'Visit',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('assigned_to')
                    ->relationship('assignedTo', 'name')
                    ->label('Assigned To'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('scheduled_date', 'asc');
    }
}
