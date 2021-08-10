<?php

declare(strict_types=1);

namespace App\Dto\MappingFields;

use Spatie\DataTransferObject\DataTransferObject;

class NumericTransformable extends DataTransferObject
{
    /** @var string [in: *, /, +, -] */
    public $operation;

    /** @var float */
    public $number;
}
