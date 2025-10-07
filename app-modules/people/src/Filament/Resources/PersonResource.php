<?php

namespace ChurchPanel\People\Filament\Resources;

use ChurchPanel\People\Filament\Resources\Pages\CreatePerson;
use ChurchPanel\People\Filament\Resources\Pages\EditPerson;
use ChurchPanel\People\Filament\Resources\Pages\ListPeople;
use ChurchPanel\People\Filament\Resources\Schemas\PersonForm;
use ChurchPanel\People\Filament\Resources\Tables\PeopleTable;
use ChurchPanel\People\Models\Person;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $slug = 'people';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'People';

    protected static ?string $modelLabel = 'Person';

    protected static ?string $pluralModelLabel = 'People';

    protected static UnitEnum|string|null $navigationGroup = 'People Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PersonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeopleTable::configure($table);
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
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
