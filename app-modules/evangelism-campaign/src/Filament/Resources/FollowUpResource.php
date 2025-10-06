<?php

namespace ChurchPanel\EvangelismCampaign\Filament\Resources;

use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\CreateFollowUp;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\EditFollowUp;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Pages\ListFollowUps;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Schemas\FollowUpForm;
use ChurchPanel\EvangelismCampaign\Filament\Resources\Tables\FollowUpTable;
use ChurchPanel\EvangelismCampaign\Models\FollowUp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FollowUpResource extends Resource
{
    protected static ?string $model = FollowUp::class;

    protected static ?string $slug = 'follow-ups';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Follow-ups';

    protected static ?string $modelLabel = 'Follow-up';

    protected static ?string $pluralModelLabel = 'Follow-ups';

    protected static UnitEnum|string|null $navigationGroup = 'Evangelism';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return FollowUpForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FollowUpTable::configure($table);
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
            'index' => ListFollowUps::route('/'),
            'create' => CreateFollowUp::route('/create'),
            'edit' => EditFollowUp::route('/{record}/edit'),
        ];
    }
}
