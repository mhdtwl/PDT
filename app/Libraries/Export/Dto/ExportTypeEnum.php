<?php

declare(strict_types=1);

namespace App\Libraries\Export\Dto;

use App\Dto\EnumInterface;
use Spatie\DataTransferObject\DataTransferObject;

class ExportTypeEnum extends DataTransferObject implements EnumInterface
{
    public const CSV = 'csv';

    public static function getTypes(): array
    {
        return [self::CSV];
    }
}