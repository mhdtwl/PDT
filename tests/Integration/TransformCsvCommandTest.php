<?php

namespace Tests\Integration;

use Illuminate\Support\Facades\Artisan;
use League\Csv\Reader;
use Tests\FeatureTestCase;

class TransformCsvCommandTest extends FeatureTestCase
{
    public function testTransformCsvCommand(): void
    {
        $inputFile = './storage/data/input.csv';
        $outputExampleFile = './storage/data/output.csv';
        $outputFile = './storage/data/output-test.csv';
        Artisan::call(
            'export:transform-patients',
            [
                '--i' => $inputFile,
                '--o' => $outputFile
            ]
        );
        $expect = Reader::createFromPath($outputExampleFile, 'r');
        $actual = Reader::createFromPath($outputFile, 'r');
        $this->assertEquals($expect->getRecords(), $actual->getRecords());
        unlink($outputFile);
    }
}
