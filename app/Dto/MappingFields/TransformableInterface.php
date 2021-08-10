<?php

namespace App\Dto\MappingFields;

interface TransformableInterface
{
    public static function getValueMapping(): array;
}