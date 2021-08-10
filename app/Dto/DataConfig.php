<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class DataConfig extends DataTransferObject
{
    /** @var \App\DTO\ConvertTypes */
    public $convertTypes;

    /** @var \App\DTO\FieldsMap[] */
    public $mapping;
}