<?php

namespace ChurchPanel\Events\Filament\Resources\EventAttendanceResource\Pages;

use ChurchPanel\Events\Filament\Resources\EventAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventAttendance extends ListRecords
{
    protected static string $resource = EventAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
