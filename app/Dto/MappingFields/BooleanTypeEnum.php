<?php

declare(strict_types=1);

namespace App\Dto\MappingFields;

use App\Dto\EnumInterface;
use Spatie\DataTransferObject\DataTransferObject;

class BooleanTypeEnum extends DataTransferObject implements EnumInterface, TransformableInterface
{
    public const ZERO_AND_ONE = 'ZeroAndOne';

    public static function getTypes(): array
    {
        return [self::ZERO_AND_ONE];
    }

    public static function getValueMapping(): array
    {
        return [self::ZERO_AND_ONE => [0 => false, 1 => true, 'Yes' => 1, 'No' => 0]];
    }
}