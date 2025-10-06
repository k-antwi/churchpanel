<?php

namespace App\Filament\Resources\Churches\Pages;

use App\Filament\Resources\Churches\ChurchResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChurch extends ViewRecord
{
    protected static string $resource = ChurchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
