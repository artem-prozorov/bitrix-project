<?php

namespace App\Services\ImageSyncer\Strategies;

use App\Collections\ImagesToLinksCollection;
use App\Services\ImageSyncer\DataObjects\Product;

interface StrategyInterface
{
    public function getRecord(Product $product, string $address, ImagesToLinksCollection $currentImages): array;
}
