<?php

namespace ChurchPanel\AuditTrail\Filament\Resources;

use BackedEnum;
use ChurchPanel\AuditTrail\Models\AuditLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
                //
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
        ];
    }
}
