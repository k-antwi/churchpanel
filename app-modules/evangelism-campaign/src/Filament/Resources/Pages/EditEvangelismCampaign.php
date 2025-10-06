<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\Pages;

use ChurchPanel\EvangelismCampaign\Filament\Resources\EvangelismCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvangelismCampaign extends EditRecord
{
    protected static string $resource = EvangelismCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
