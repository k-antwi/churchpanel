<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource\Pages;

use ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitation extends EditRecord
{
    protected static string $resource = VisitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
