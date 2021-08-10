<?php

declare(strict_types=1);

namespace App\Libraries\Export\Csv;

use App\Libraries\Export\ExportReaderInterface;
use Illuminate\Support\Collection;
use League\Csv\Exception;
use League\Csv\Reader;

class CsvReader implements ExportReaderInterface
{
    /** @throws Exception */
    public function readFileByPath(string $filePath): Collection
    {
        $reader = Reader::createFromPath($filePath, 'r');
        $reader->setHeaderOffset(0);
        $reader->setDelimiter(';');
        return collect($reader->getRecords());
    }
}
