<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecificationResource\Pages;
use App\Filament\Resources\SpecificationResource\RelationManagers;
use App\Models\Specification;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class SpecificationResource extends Resource
{
    protected static ?string $model = Specification::class;

    protected static ?string $navigationGroup = 'Item Management';
    protected static ?int $navigationSort = 3;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->live()
                    ->preload(),
                Select::make('sub_category_id')
                    ->label('Subcategory')
                    ->relationship('subCategory', 'name')
                    ->options(fn(Get $get): Collection => SubCategory::query()
                        ->where('category_id', $get('category_id'))
                        ->pluck('name', 'id')),
                Repeater::make('specifications')
                    ->schema([
                        TextInput::make('name')
                            ->label('Specification Name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->label('Specifications')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subCategory.name')
                    ->label('Subcategory')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSpecifications::route('/'),
            'create' => Pages\CreateSpecification::route('/create'),
            'edit' => Pages\EditSpecification::route('/{record}/edit'),
        ];
    }
}
