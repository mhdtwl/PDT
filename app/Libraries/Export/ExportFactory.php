<?php

declare(strict_types=1);

namespace App\Libraries\Export;

use App\Libraries\Export\Csv\CsvWriter;
use App\Libraries\Export\Dto\ExportTypeEnum;
use App\Libraries\Export\Csv\CsvReader;
use Exception;

class ExportFactory
{
    private const ERR_MSG_EXPORT_NOT_FOUND = 'Export reader not found for type: ';

    /** @throws Exception */
    public function getReader(string $type): ExportReaderInterface
    {
        switch ($type) {
            case ExportTypeEnum::CSV:
                return new CsvReader();
            default:
                throw new Exception(self::ERR_MSG_EXPORT_NOT_FOUND . $type);
        }
    }

    /** @throws Exception */
    public function getWriter(string $type): ExportWriterInterface
    {
        switch ($type) {
            case ExportTypeEnum::CSV:
                return new CsvWriter();
            default:
                throw new Exception(self::ERR_MSG_EXPORT_NOT_FOUND . $type);
        }
    }
}
