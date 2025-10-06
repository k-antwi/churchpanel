<?php

namespace ChurchPanel\People\Filament\Resources\Pages;

use ChurchPanel\People\Filament\Resources\PersonResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerson extends CreateRecord
{
    protected static string $resource = PersonResource::class;
}
