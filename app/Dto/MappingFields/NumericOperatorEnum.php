<?php

declare(strict_types=1);

namespace App\Dto\MappingFields;

use App\Dto\EnumInterface;
use Spatie\DataTransferObject\DataTransferObject;

class NumericOperatorEnum extends DataTransferObject implements EnumInterface
{
    public const MULTIPLY = '*';
    public const DIVISION = '/';
    public const ADD = '+';
    public const SUBTRACT = '-';

    public static function getTypes(): array
    {
        return [self::MULTIPLY, self::DIVISION, self::ADD, self::SUBTRACT];
    }
}