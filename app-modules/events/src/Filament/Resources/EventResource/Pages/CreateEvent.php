<?php

namespace ChurchPanel\Events\Filament\Resources\EventResource\Pages;

use ChurchPanel\Events\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
