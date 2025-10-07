<?php

namespace ChurchPanel\CpCore\Filament\Resources\Churches\Pages;

use ChurchPanel\CpCore\Filament\Resources\Churches\ChurchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChurches extends ListRecords
{
    protected static string $resource = ChurchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
