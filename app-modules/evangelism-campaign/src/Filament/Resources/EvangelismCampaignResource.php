<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources;

use ChurchPanel\EvangelismCampaign\Filament\Resources\EvangelismCampaignResource\RelationManagers\CapturedContactsRelationManager;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\CreateEvangelismCampaign;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\EditEvangelismCampaign;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\ListEvangelismCampaigns;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas\EvangelismCampaignForm;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Tables\EvangelismCampaignTable;
use ChurchPanel\EvangelismCampaign\Models\EvangelismCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EvangelismCampaignResource extends Resource
{
    protected static ?string $model = EvangelismCampaign::class;

    protected static ?string $slug = 'evangelism-campaigns';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Evangelism Campaigns';

    protected static ?string $modelLabel = 'Evangelism Campaign';

    protected static ?string $pluralModelLabel = 'Evangelism Campaigns';

    protected static UnitEnum|string|null $navigationGroup = 'Evangelism';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EvangelismCampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvangelismCampaignTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CapturedContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvangelismCampaigns::route('/'),
            'create' => CreateEvangelismCampaign::route('/create'),
            'edit' => EditEvangelismCampaign::route('/{record}/edit'),
        ];
    }
}
