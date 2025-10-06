<?php

namespace ChurchPanel\People\Filament\Resources\Pages;

use ChurchPanel\People\Filament\Resources\PersonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeople extends ListRecords
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
