<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VisitationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contact.person.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->label('Contact'),

                TextColumn::make('visitor.name')
                    ->searchable()
                    ->sortable()
                    ->label('Visited By'),

                TextColumn::make('visit_date')
                    ->date()
                    ->sortable()
                    ->label('Visit Date'),

                TextColumn::make('purpose')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('attendance_status')
                    ->badge()
                    ->colors([
                        'success' => 'home',
                        'warning' => 'not_home',
                        'danger' => 'moved',
                    ])
                    ->searchable()
                    ->sortable()
                    ->label('Status'),

                TextColumn::make('duration_minutes')
                    ->numeric()
                    ->suffix(' min')
                    ->sortable()
                    ->toggleable()
                    ->label('Duration'),

                IconColumn::make('follow_up_required')
                    ->boolean()
                    ->sortable()
                    ->label('Follow-up'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('visited_by')
                    ->relationship('visitor', 'name')
                    ->label('Visited By'),
                SelectFilter::make('attendance_status')
                    ->options([
                        'home' => 'Home',
                        'not_home' => 'Not Home',
                        'moved' => 'Moved',
                    ])
                    ->label('Status'),
                SelectFilter::make('follow_up_required')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ])
                    ->label('Follow-up Required'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('visit_date', 'desc');
    }
}
