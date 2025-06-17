<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ProductSpecification;
use App\Models\Specification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

}
