<?php

declare(strict_types=1);

namespace App\Dto\MappingFields;

use App\Dto\EnumInterface;
use Spatie\DataTransferObject\DataTransferObject;

class FieldTypeEnum extends DataTransferObject implements EnumInterface
{
    public const NUMERIC = 'numeric';
    public const BOOLEAN = 'boolean';
    public const DATE = 'date';
    public const TEXTUAL = 'textual';

    public static function getTypes(): array
    {
        return [self::NUMERIC, self::BOOLEAN, self::DATE, self::TEXTUAL];
    }
}