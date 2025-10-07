<?php

namespace ChurchPanel\AuditTrail\Filament\Resources\AuditResource\Pages;

use ChurchPanel\AuditTrail\Filament\Resources\AuditResource;
use Filament\Resources\Pages\ListRecords;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditResource::class;
}
