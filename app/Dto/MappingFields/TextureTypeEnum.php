<?php

declare(strict_types=1);

namespace App\Dto\MappingFields;

use App\Dto\EnumInterface;
use Spatie\DataTransferObject\DataTransferObject;

class TextureTypeEnum extends DataTransferObject implements EnumInterface, TransformableInterface
{
    public const MALE_1_FEMALE_2 = 'Male1Female2';

    public static function getTypes(): array
    {
        return [self::MALE_1_FEMALE_2];
    }

    public static function getValueMapping(): array
    {
        return [
            self::MALE_1_FEMALE_2 => [1 => 'Male', 2 => 'Female', 'Male' => 1, 'Female' => 2],
        ];
    }
}