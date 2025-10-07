<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource\Pages;

use ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscipleshipJourneys extends ListRecords
{
    protected static string $resource = DiscipleshipJourneyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
