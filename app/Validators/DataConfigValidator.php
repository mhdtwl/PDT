<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use RuntimeException;

class DataConfigValidator
{
    private const ERR_MSG_VALIDATE_JSON_DATA_CONFIG = "Error to on validate json schema ";

    public function validateJsonSchema(string $json): bool
    {
        $data = json_decode($json, true);
        $rules = [
            //Todo add Validation - Ticket #????
//            "ConvertTypes" => 'required',
//            "ConvertTypes.source" => 'required|string',
//            "ConvertTypes.target" => 'required|string',
//            "Mapping" => 'required',
//            "Mapping.source_name" => 'required|string',
//            "Mapping.target_name" => 'required|string',
//            "Mapping.target_order" => 'required|integer',
//            "Mapping.target_order" => 'required|integer',
//            "Mapping.type" => 'required|string',
//            "Mapping.transform" => 'nullable|in:null,string,array',
//            "Mapping.transform.operation" => 'nullable|string',
//            "Mapping.transform.number" => 'nullable|integer',
        ];

        $validator = Validator::make(json_decode(json_encode($data), true), $rules);
        if ($validator->passes()) {
            return true;
        } else {
            throw new RuntimeException(self::ERR_MSG_VALIDATE_JSON_DATA_CONFIG . var_dump($validator->errors()->all()));
        }
    }
}
