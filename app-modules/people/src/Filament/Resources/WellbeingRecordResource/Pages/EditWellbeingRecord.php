<?php

namespace ChurchPanel\People\Filament\Resources\WellbeingRecordResource\Pages;

use ChurchPanel\People\Filament\Resources\WellbeingRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWellbeingRecord extends EditRecord
{
    protected static string $resource = WellbeingRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
