<?php

namespace ChurchPanel\Events\Filament\Resources\ServiceTypeResource\Pages;

use ChurchPanel\Events\Filament\Resources\ServiceTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceTypes extends ListRecords
{
    protected static string $resource = ServiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
