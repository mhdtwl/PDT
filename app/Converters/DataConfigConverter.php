<?php

declare(strict_types=1);

namespace App\Converters;

use App\Dto\ConvertTypes;
use App\Dto\DataConfig;
use App\Dto\FieldsMap;
use App\DTO\MappingFields\FieldTypeEnum;
use App\Dto\MappingFields\NumericOperatorEnum;
use App\DTO\MappingFields\NumericTransformable;
use App\DTO\MappingFields\TransformableInterface;
use RuntimeException;

class DataConfigConverter
{
    //ToDo move `undefined types` to validation class [DataConfigValidator]. ticker #????
    private const ERR_MSG_UNDEFINED_TYPE = 'Undefined mapping type: ';
    private const ERR_MSG_UNDEFINED_NUMERIC_OPERATOR = 'Undefined mapping operator of numeric: ';
    private const ERR_MSG_UNDEFINED_TRANSFORM_TYPE = 'Transform must be defined: ';
    private const ERR_MSG_DUPLICATE_SOURCE_FIELD_AS_KEY = 'Duplicate source field key ';

    public function getDataConfigFromJson(array $object): DataConfig
    {
        return new DataConfig(
            [
                'convertTypes' => $this->getConvertTypes($object['ConvertTypes']),
                'mapping' => $this->getMapping($object['Mapping']),
            ]
        );
    }

    private function getConvertTypes(array $types): ConvertTypes
    {
        return new ConvertTypes(
            [
                'source' => $types['source'],
                'target' => $types['target'],
            ]
        );
    }

    private function getMapping(array $mapping): array
    {
        $fieldMaps = array_map([$this, 'getFieldsMap'], $mapping);

        $fieldMapsWithKeys = [];
        foreach ($fieldMaps as $field) {
            /** @var FieldsMap $field */
            if (in_array($field->source_name, array_keys($fieldMapsWithKeys))) {
                throw new RuntimeException(self::ERR_MSG_DUPLICATE_SOURCE_FIELD_AS_KEY . $field->source_name);
            }
            $fieldMapsWithKeys[$field->source_name] = $field;
        }
        return $fieldMapsWithKeys;
    }

    private function getFieldsMap(array $mapping): FieldsMap
    {
        if (!in_array($mapping['type'], FieldTypeEnum::getTypes())) {
            throw new RuntimeException(self::ERR_MSG_UNDEFINED_TYPE . $mapping['type']);
        }

        $transform = (isset($mapping['transform']))
            ? $this->getTransformMapping($mapping['type'], $mapping['transform'])
            : null;

        return new FieldsMap(
            [
                'source_name' => $mapping['source_name'],
                'target_name' => $mapping['target_name'],
                'reference_key' => $mapping['reference_key'],
                'target_order' => $mapping['target_order'],
                'type' => $mapping['type'],
                'transform' => $transform
            ]
        );
    }

    /** @return TransformableInterface|string */
    private function getTransformMapping(string $type, $transform)
    {
        if (is_array($transform) && $type === FieldTypeEnum::NUMERIC) {
            return $this->getTransformNumeric($transform);
        }

        if (is_string($transform)) {
            return $transform;
        }

        throw new RuntimeException(self::ERR_MSG_UNDEFINED_TRANSFORM_TYPE . var_dump($transform));
    }

    private function getTransformNumeric(array $transform): NumericTransformable
    {
        $number = (float)$transform['number'];
        $operator = $transform['operation'];

        if (!in_array($transform['operation'], NumericOperatorEnum::getTypes())) {
            throw new RuntimeException(self::ERR_MSG_UNDEFINED_NUMERIC_OPERATOR . $transform['operation']);
        }

        return new NumericTransformable(
            [
                'operation' => $operator,
                'number' => $number,
            ]
        );
    }
}
