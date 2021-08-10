<?php

declare(strict_types=1);

namespace App\Services\Transform;

use App\Converters\RecordPatientConverter;
use App\Dto\DataConfig;
use Illuminate\Support\Collection;

class PatientService
{
    private const CHUNCK_LIMIT = 200;

    /**  @var RecordPatientConverter */
    private $patientConverter;

    public function __construct(RecordPatientConverter $patientConverter)
    {
        $this->patientConverter = $patientConverter;
    }

    /**
     * ToDo: To support large dataset with thoudands of record.
     * $chunkPatientRecords = $patientsData->chunk(self::CHUNCK_LIMIT);
     */
    public function transformData(Collection $patientsData, DataConfig $config): Collection
    {
        $patientRecords = [];
        foreach ($patientsData as $patientDetails) {
            $patientRecords[] = $this->patientConverter
                ->buildTransformedPatientRecord($patientDetails, $config);
        }

        return collect(['header' => $this->patientConverter->getHeader(), 'data' => $patientRecords]);
    }
}