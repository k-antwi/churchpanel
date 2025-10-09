<?php

namespace ChurchPanel\AuditTrail\Filament\Resources;

use BackedEnum;
use ChurchPanel\AuditTrail\Models\AuditLog;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AuditResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $slug = 'audit-logs';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Audit Logs';

    protected static ?string $modelLabel = 'Audit Log';

    protected static ?string $pluralModelLabel = 'Audit Logs';

    protected static UnitEnum|string|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 99;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->auditEvents())
            ->columns([
                TextColumn::make('event_type')
                    ->label('Event')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'Created' => 'success',
                        'Updated' => 'warning',
                        'Deleted' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('model_type')
                    ->label('Model')
                    ->searchable(),

                TextColumn::make('model_id')
                    ->label('Model ID')
                    ->searchable(),

                TextColumn::make('user')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('data.ip_address')
                    ->label('IP Address')
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('happened_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('happened_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        'Created' => 'Created',
                        'Updated' => 'Updated',
                        'Deleted' => 'Deleted',
                    ])
                    ->attribute('type')
                    ->query(function (Builder $query, array $data) {
                        if (!$data['value']) {
                            return $query;
                        }

                        return $query->where('type', match($data['value']) {
                            'Created' => 'ChurchPanel\AuditTrail\Events\ModelCreated',
                            'Updated' => 'ChurchPanel\AuditTrail\Events\ModelUpdated',
                            'Deleted' => 'ChurchPanel\AuditTrail\Events\ModelDeleted',
                            default => null,
                        });
                    }),

                Tables\Filters\Filter::make('model_type')
                    ->form([
                        \Filament\Forms\Components\Select::make('model')
                            ->options([
                                'Church' => 'Church',
                                'Branch' => 'Branch',
                                'Person' => 'Person',
                                'Contact' => 'Contact',
                                'Event' => 'Event',
                                'ServiceType' => 'Service Type',
                                'WellbeingRecord' => 'Wellbeing Record',
                            ])
                            ->placeholder('All Models'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['model'],
                            fn (Builder $query, $model): Builder => $query->whereJsonContains('data->model_type', $model)
                        );
                    }),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('happened_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('happened_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => \ChurchPanel\AuditTrail\Filament\Resources\AuditResource\Pages\ListAudits::route('/'),
            'view' => \ChurchPanel\AuditTrail\Filament\Resources\AuditResource\Pages\ViewAudit::route('/{record}'),
        ];
    }
}
