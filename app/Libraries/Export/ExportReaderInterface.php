<?php

declare(strict_types=1);

namespace App\Libraries\Export;

use Illuminate\Support\Collection;

interface ExportReaderInterface
{
    public function readFileByPath(string $filePath): Collection;
}