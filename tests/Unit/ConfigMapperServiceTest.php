<?php

namespace Tests\Unit;

use App\Converters\DataConfigConverter;
use App\Dto\ConvertTypes;
use App\Dto\DataConfig;
use App\Dto\FieldsMap;
use App\Services\Transform\ConfigMapperService;
use App\Validators\DataConfigValidator;
use Tests\TestCase;

class ConfigMapperServiceTest extends TestCase
{
    /**
     * @dataProvider configJsonProvider
     */
    public function testGetDataConfigByJson(?DataConfig $expected, string $inputConfig)
    {
        $mapper = new ConfigMapperService(new DataConfigValidator(), new DataConfigConverter());
        $actualResult = $mapper->getDataConfigByJson($inputConfig);
        $this->assertEquals($expected, $actualResult);
    }

    public function configJsonProvider(): array
    {
        $config1 = $this->getConfig1();
        $config2 = $this->getConfig2();
        return [
            [$config1['object'], $config1['json']],
            [$config2['object'], $config2['json']],
        ];
    }

    private function getConfig1(): array
    {
        $objectConfig = [
            'ConvertTypes' => ['source' => 'csv', 'target' => 'csv'],
            'Mapping' => [
                [
                    'source_name' => "Patient ID",
                    'target_name' => "record_id",
                    'reference_key' => "recordId",
                    'target_order' => 1,
                    'type' => "textual",
                    'transform' => null
                ],
                [
                    'source_name' => "Weight",
                    'target_name' => "weight_kg",
                    'reference_key' => "weight",
                    'target_order' => 4,
                    'type' => "numeric",
                    'transform' => null
                ],
                [
                    "source_name" => "Pregnant",
                    "target_name" => "pregnant",
                    "reference_key" => "pregnant",
                    "target_order" => 5,
                    "type" => "boolean",
                    "transform" => "ZeroAndOne"
                ],
            ]
        ];
        $object = $this->getDataConfig($objectConfig);
        $json = json_encode(json_decode(file_get_contents("./storage/testing/json/data-config.json")));
        return ['json' => $json, 'object' => $object];
    }

    private function getConfig2(): array
    {
        $objectConfig = [
            'ConvertTypes' => ['source' => 'json', 'target' => 'json'],
            'Mapping' => [
                [
                    'source_name' => "Patient ID",
                    'target_name' => "record_id",
                    'reference_key' => "recordId",
                    'target_order' => 1,
                    'type' => "textual",
                    'transform' => null
                ],
                [
                    "source_name" => "Pregnant",
                    "target_name" => "pregnant",
                    "reference_key" => "pregnant",
                    "target_order" => 5,
                    "type" => "boolean",
                    "transform" => "ZeroAndOne"
                ],
            ]
        ];
        $object = $this->getDataConfig($objectConfig);
        $json = json_encode(json_decode(file_get_contents("./storage/testing/json/data-config2.json")));
        return ['json' => $json, 'object' => $object];
    }

    public function getDataConfig($object): DataConfig
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
            $fieldMapsWithKeys[$field->source_name] = $field;
        }
        return $fieldMapsWithKeys;
    }

    private function getFieldsMap(array $mapping): FieldsMap
    {
        return new FieldsMap(
            [
                'source_name' => $mapping['source_name'],
                'target_name' => $mapping['target_name'],
                'reference_key' => $mapping['reference_key'],
                'target_order' => $mapping['target_order'],
                'type' => $mapping['type'],
                'transform' => $mapping['transform']
            ]
        );
    }
}
