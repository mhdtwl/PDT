<?php

declare(strict_types=1);

namespace App\Libraries\Export;

use Illuminate\Support\Collection;

interface ExportWriterInterface
{
    //public function readFileByPath(string $filePath): Collection;
    public function saveFileByPath(string $filePath, Collection $records): void;
}