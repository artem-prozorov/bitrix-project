<?php

namespace App\Services\ImageSyncer\Strategies;

use App\Collections\ImagesToLinksCollection;

interface StrategyInterface
{
    public function getRecord(array $product, string $address, ImagesToLinksCollection $currentImages): array;
}
