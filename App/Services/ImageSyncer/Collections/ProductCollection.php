<?php

namespace App\Services\ImageSyncer\Collections;

use Gamez\Illuminate\Support\TypedCollection;
use App\Services\ImageSyncer\DataObjects\Product;

class ProductCollection extends TypedCollection
{
    protected static $allowedTypes = [Product::class];
}
