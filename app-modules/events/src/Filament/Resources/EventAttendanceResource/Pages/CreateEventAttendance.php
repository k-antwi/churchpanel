<?php

namespace ChurchPanel\Events\Filament\Resources\EventAttendanceResource\Pages;

use ChurchPanel\Events\Filament\Resources\EventAttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEventAttendance extends CreateRecord
{
    protected static string $resource = EventAttendanceResource::class;
}
