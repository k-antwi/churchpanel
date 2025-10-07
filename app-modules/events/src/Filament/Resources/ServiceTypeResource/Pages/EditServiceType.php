<?php

namespace ChurchPanel\Events\Filament\Resources\ServiceTypeResource\Pages;

use ChurchPanel\Events\Filament\Resources\ServiceTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceType extends EditRecord
{
    protected static string $resource = ServiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
