<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource\Pages;

use ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitations extends ListRecords
{
    protected static string $resource = VisitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
