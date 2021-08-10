<?php

declare(strict_types=1);

namespace App\Converters;

use App\Dto\DataConfig;
use App\Dto\FieldsMap;
use App\Dto\MappingFields\BooleanTypeEnum;
use App\Dto\MappingFields\FieldTypeEnum;
use App\Dto\MappingFields\NumericOperatorEnum;
use App\Dto\MappingFields\TextureTypeEnum;
use DateTime;
use Throwable;

class RecordPatientConverter
{
    private const ERR_MSG_NUMERIC_OPERATOR_NOT_FOUND = "numeric operator not found";

    /** @var array */
    private $header = [];

    public function getHeader(): array
    {
        return array_merge(...$this->header);
    }

    /** @throws Throwable */
    public function buildTransformedPatientRecord(array $patientDetails, DataConfig $config): array //PatientRecord
    {
        $fields = [];
        foreach ($patientDetails as $fieldKey => $fieldValue) {
            if (isset($config->mapping[$fieldKey])) {
                /**@var FieldsMap */
                $fieldConfig = $config->mapping[$fieldKey];
                $transformedField = (string)$this->getTransformed($fieldValue, $fieldConfig);
                $fields[$fieldConfig->target_order][$fieldConfig->reference_key] = $transformedField;
                $this->header[$fieldConfig->target_order][$fieldConfig->reference_key] = $fieldConfig->target_name;
            }
        }
        return array_merge(...$fields);
    }

    /** @throws Throwable */
    private function getTransformed(string $fieldValue, FieldsMap $fieldConfig)
    {
        if ($fieldConfig->transform) {
            $configType = $fieldConfig->type;
            switch ($configType) {
                case FieldTypeEnum::TEXTUAL:
                    return $this->getFieldValueOfTextureType($fieldValue, $fieldConfig);
                case FieldTypeEnum::DATE:
                    return $this->getFieldValueOfDateType($fieldValue, $fieldConfig);
                case FieldTypeEnum::BOOLEAN:
                    return $this->getFieldValueOfBooleanType($fieldValue, $fieldConfig);
                case FieldTypeEnum::NUMERIC:
                    return $this->getFieldValueOfNumericType($fieldValue, $fieldConfig);
                default:
                    break;
            }
        }

        return $fieldValue;
    }

    /** @throws Throwable */
    private function getFieldValueOfDateType(string $fieldValue, FieldsMap $fieldConfig)
    {
        return date_format(new DateTime($fieldValue), $fieldConfig->transform);
    }

    private function getFieldValueOfTextureType(string $fieldValue, FieldsMap $fieldConfig)
    {
        if ($fieldConfig->transform === TextureTypeEnum::MALE_1_FEMALE_2) {
            return TextureTypeEnum::getValueMapping()[TextureTypeEnum::MALE_1_FEMALE_2][$fieldValue];
        }
        return $fieldValue;
    }

    private function getFieldValueOfBooleanType(string $fieldValue, FieldsMap $fieldConfig)
    {
        if ($fieldConfig->transform === BooleanTypeEnum::ZERO_AND_ONE) {
            return filter_var($fieldValue, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        return filter_var($fieldValue, FILTER_VALIDATE_BOOLEAN);
    }

    private function getFieldValueOfNumericType(string $fieldValue, FieldsMap $fieldConfig)
    {
        $operation = $fieldConfig->transform->operation;
        $number = $fieldConfig->transform->number;
        if (empty($fieldValue)) {
            return null;
        }
        switch ($operation) {
            case NumericOperatorEnum::MULTIPLY:
                return (float)$fieldValue * $number;
            case NumericOperatorEnum::DIVISION:
                return (float)$fieldValue / $number;
            case NumericOperatorEnum::ADD:
                return (float)$fieldValue + $number;
            case NumericOperatorEnum::SUBTRACT:
                return (float)$fieldValue - $number;
            default:
                throw RuntimeException(self::ERR_MSG_NUMERIC_OPERATOR_NOT_FOUND);
        }
    }
}
