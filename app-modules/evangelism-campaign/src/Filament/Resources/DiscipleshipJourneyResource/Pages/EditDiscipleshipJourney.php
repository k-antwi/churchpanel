<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource\Pages;

use ChurchPanel\EvangelismCampaign\Filament\Resources\DiscipleshipJourneyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscipleshipJourney extends EditRecord
{
    protected static string $resource = DiscipleshipJourneyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
