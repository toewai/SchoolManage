<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Specification;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Item Management';
    protected static ?int $navigationSort = 4;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->live()
                    ->required(),
                Select::make('sub_category_id')
                    ->label('Sub Category')
                    ->relationship('subCategory', 'name')
                    ->live()
                    ->nullable()
                    ->options(fn(Get $get): Collection => SubCategory::query()
                        ->where('category_id', $get('category_id'))
                        ->pluck('name', 'id')),
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Product::class, 'slug', fn($record) => $record),
                TextInput::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(1000),
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('discount_price')
                    ->label('Discount Price')
                    ->nullable()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                TextInput::make('sku')
                    ->label('SKU')
                    ->nullable()
                    ->maxLength(100)
                    ->unique(Product::class, 'sku', fn($record) => $record),
                TextInput::make('barcode')
                    ->label('Barcode')
                    ->nullable()
                    ->maxLength(100)
                    ->unique(Product::class, 'barcode', fn($record) => $record),
                FileUpload::make('image')
                    ->label('Product Image')
                    ->image()
                    ->disk('public')
                    ->directory('product_images')
                    ->nullable(),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),
                Toggle::make('is_featured')
                    ->label('Is Featured')
                    ->default(false),
                Toggle::make('is_new')
                    ->label('Is New')
                    ->default(false),
                Toggle::make('is_best_seller')
                    ->label('Is Best Seller')
                    ->default(false),
                Toggle::make('is_on_sale')
                    ->label('Is On Sale')
                    ->default(false),
                Repeater::make('specifications')
                    ->label('Specifications')
                    ->deletable(false)
                    ->addable(false)
                    ->schema(function (Get $get) {
                        $fields = [];
                        $specifications = Specification::where('category_id', $get('category_id'))
                            ->where('sub_category_id', $get('sub_category_id'))
                            ->get();
                        if ($specifications->isEmpty()) {
                            $specifications = Specification::where('category_id', $get('category_id'))
                                ->whereNull('sub_category_id')
                                ->get();
                        }
                        foreach ($specifications as $spec) {
                            foreach ($spec->specifications as $key => $value) {
                                $fields[] = TextInput::make($value['name'])
                                    ->label($value['name'])
                                    ->required();
                            }
                        }
                        return $fields;
                    })
                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
