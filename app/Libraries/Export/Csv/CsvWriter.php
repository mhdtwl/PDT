<?php

declare(strict_types=1);

namespace App\Libraries\Export\Csv;

use App\Libraries\Export\ExportWriterInterface;
use Illuminate\Support\Collection;
use League\Csv\CannotInsertRecord;
use League\Csv\InvalidArgument;
use League\Csv\Writer;

class CsvWriter implements ExportWriterInterface
{
    /**
     * @throws InvalidArgument
     * @throws CannotInsertRecord
     */
    public function saveFileByPath(string $filePath, Collection $data): void
    {
        $header = $data->get('header');
        $records = $data->get('data');
        $writer = Writer::createFromPath($filePath, 'w+');
        $writer->setDelimiter(';')->insertOne($header);
        $writer->insertAll($records);
    }
}
