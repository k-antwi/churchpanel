<?php

namespace ChurchPanel\Events\Filament\Resources\EventAttendanceResource\Pages;

use ChurchPanel\Events\Filament\Resources\EventAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventAttendance extends EditRecord
{
    protected static string $resource = EventAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
