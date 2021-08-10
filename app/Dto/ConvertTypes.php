<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class ConvertTypes extends DataTransferObject
{
    /** @var string */
    public $source;

    /** @var string */
    public $target;
}
