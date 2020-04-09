<?php

namespace App\Services\ImageSyncer\Collections;

use Gamez\Illuminate\Support\TypedCollection;
use App\Services\ImageSyncer\DataObjects\RecordToProcess;

class RecordsToProcessCollection extends TypedCollection
{
    protected static $allowedTypes = [RecordToProcess::class];
}
