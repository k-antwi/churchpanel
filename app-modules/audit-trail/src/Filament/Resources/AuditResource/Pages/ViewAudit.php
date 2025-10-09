<?php

namespace ChurchPanel\AuditTrail\Filament\Resources\AuditResource\Pages;

use ChurchPanel\AuditTrail\Filament\Resources\AuditResource;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewAudit extends ViewRecord
{
    protected static string $resource = AuditResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Event Information')
                    ->schema([
                        Placeholder::make('event_type')
                            ->label('Event Type')
                            ->content(fn ($record) => match($record->event_type) {
                                'Created' => '✓ Created',
                                'Updated' => '↻ Updated',
                                'Deleted' => '✗ Deleted',
                                default => $record->event_type,
                            }),

                        Placeholder::make('happened_at')
                            ->label('Timestamp')
                            ->content(fn ($record) => $record->happened_at?->format('F j, Y g:i:s A') ?? 'N/A'),

                        Placeholder::make('model_type')
                            ->label('Model Type')
                            ->content(fn ($record) => $record->model_type),

                        Placeholder::make('model_id')
                            ->label('Model ID')
                            ->content(fn ($record) => $record->model_id ?? 'N/A'),
                    ])
                    ->columns(2),

                Section::make('User Information')
                    ->schema([
                        Placeholder::make('user')
                            ->label('User')
                            ->content(fn ($record) => $record->user ?? 'System'),

                        Placeholder::make('user_id')
                            ->label('User ID')
                            ->content(fn ($record) => $record->data['user_id'] ?? 'N/A'),

                        Placeholder::make('ip_address')
                            ->label('IP Address')
                            ->content(fn ($record) => $record->data['ip_address'] ?? 'N/A'),

                        Placeholder::make('user_agent')
                            ->label('User Agent')
                            ->content(fn ($record) => str($record->data['user_agent'] ?? 'N/A')->limit(100)),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Section::make('Old Values')
                    ->schema([
                        Placeholder::make('old_values_display')
                            ->label('')
                            ->content(function ($record) {
                                $oldValues = $record->data['old_values'] ?? [];
                                if (empty($oldValues)) {
                                    return 'No old values recorded';
                                }

                                $html = '<div class="space-y-2">';
                                foreach ($oldValues as $key => $value) {
                                    $displayValue = is_array($value) ? json_encode($value) : (string) $value;
                                    $html .= '<div class="grid grid-cols-3 gap-4">';
                                    $html .= '<div class="font-semibold text-gray-700 dark:text-gray-300">' . htmlspecialchars($key) . '</div>';
                                    $html .= '<div class="col-span-2 text-gray-600 dark:text-gray-400">' . htmlspecialchars($displayValue) . '</div>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->collapsed()
                    ->hidden(fn ($record) => empty($record->data['old_values'] ?? [])),

                Section::make('New Values')
                    ->schema([
                        Placeholder::make('new_values_display')
                            ->label('')
                            ->content(function ($record) {
                                $newValues = $record->data['new_values'] ?? [];
                                if (empty($newValues)) {
                                    return 'No new values recorded';
                                }

                                $html = '<div class="space-y-2">';
                                foreach ($newValues as $key => $value) {
                                    $displayValue = is_array($value) ? json_encode($value) : (string) $value;
                                    $html .= '<div class="grid grid-cols-3 gap-4">';
                                    $html .= '<div class="font-semibold text-gray-700 dark:text-gray-300">' . htmlspecialchars($key) . '</div>';
                                    $html .= '<div class="col-span-2 text-gray-600 dark:text-gray-400">' . htmlspecialchars($displayValue) . '</div>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->collapsed()
                    ->hidden(fn ($record) => empty($record->data['new_values'] ?? [])),

                Section::make('Metadata')
                    ->schema([
                        Placeholder::make('metadata_display')
                            ->label('')
                            ->content(function ($record) {
                                $metadata = $record->metadata ?? [];
                                if (empty($metadata)) {
                                    return 'No metadata';
                                }

                                $html = '<div class="space-y-2">';
                                foreach ($metadata as $key => $value) {
                                    $displayValue = is_array($value) ? json_encode($value) : (string) $value;
                                    $html .= '<div class="grid grid-cols-3 gap-4">';
                                    $html .= '<div class="font-semibold text-gray-700 dark:text-gray-300">' . htmlspecialchars($key) . '</div>';
                                    $html .= '<div class="col-span-2 text-gray-600 dark:text-gray-400">' . htmlspecialchars($displayValue) . '</div>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->collapsed()
                    ->hidden(fn ($record) => empty($record->metadata)),

                Section::make('Raw Event Data')
                    ->schema([
                        Placeholder::make('raw_data')
                            ->label('')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                '<pre class="text-xs overflow-auto p-4 bg-gray-100 dark:bg-gray-800 rounded">' .
                                htmlspecialchars(json_encode($record->data, JSON_PRETTY_PRINT)) .
                                '</pre>'
                            )),
                    ])
                    ->collapsed(),
            ]);
    }
}
