<?php

namespace ChurchPanel\People\Filament\Resources\WellbeingRecordResource\Pages;

use ChurchPanel\People\Filament\Resources\WellbeingRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWellbeingRecords extends ListRecords
{
    protected static string $resource = WellbeingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
