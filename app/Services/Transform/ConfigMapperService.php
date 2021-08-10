<?php

declare(strict_types=1);

namespace App\Services\Transform;

use App\Converters\DataConfigConverter;
use App\Dto\DataConfig;
use App\Validators\DataConfigValidator;

class ConfigMapperService
{
    private const ERR_MSG_INVALID_MAPPING_SOURCE = 'Invalid parsing mapping source ';

    /** @var DataConfigValidator */
    private $validator;

    /** @var DataConfigConverter */
    private $converter;

    public function __construct(DataConfigValidator $validator, DataConfigConverter $converter)
    {
        $this->validator = $validator;
        $this->converter = $converter;
    }

    public function getDataConfigByJson(string $json): DataConfig
    {
        $isValid = $this->validator->validateJsonSchema($json);
        if (!$isValid) {
            throw new RuntimeException(self::ERR_MSG_INVALID_MAPPING_SOURCE);
        }

        $dataConfig = json_decode($json, true);

        return $this->converter->getDataConfigFromJson($dataConfig);
    }
}
