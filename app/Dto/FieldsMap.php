<?php

declare(strict_types=1);

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class FieldsMap extends DataTransferObject
{
    /** @var string */
    public $source_name;

    /** @var string */
    public $target_name;

    /** @var string */
    public $reference_key;

    /** @var int */
    public $target_order;

    /** @var string [in:FieldTypeEnum: numeric, boolean, date, textual] */
    public $type;

    /** @var null|string|\App\DTO\MappingFields|\Spatie\DataTransferObject\DataTransferObject */
    public $transform;

}