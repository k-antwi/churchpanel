<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources;

use ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource\Pages\CreateVisitation;
use ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource\Pages\EditVisitation;
use ChurchPanel\EvangelismCampaign\Filament\Resources\VisitationResource\Pages\ListVisitations;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas\VisitationForm;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Tables\VisitationTable;
use ChurchPanel\EvangelismCampaign\Models\Visitation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class VisitationResource extends Resource
{
    protected static ?string $model = Visitation::class;

    protected static ?string $slug = 'visitations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Visitations';

    protected static ?string $modelLabel = 'Visitation';

    protected static ?string $pluralModelLabel = 'Visitations';

    protected static UnitEnum|string|null $navigationGroup = 'Evangelism';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return VisitationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisitationTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisitations::route('/'),
            'create' => CreateVisitation::route('/create'),
            'edit' => EditVisitation::route('/{record}/edit'),
        ];
    }
}
