<?php

declare(strict_types=1);

namespace App\Console\Commands\Export;

use App\Libraries\Export\ExportFactory;
use App\Services\Transform\ConfigMapperService;
use App\Services\Transform\PatientService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

class TransformPatientsCommand extends Command
{
    private const OPTION_IN = 'i';
    private const OPTION_OUT = 'o';
    private const OPTION_MAP = 'm';

    /** @var ConfigMapperService */
    private $transformMapper;

    /**  @var PatientService */
    private $patientService;

    /** @var ExportFactory */
    private $exportFactory;

    public function __construct(
        ConfigMapperService $transformMapper,
        ExportFactory $exportFactory,
        PatientService $patientService
    ) {
        parent::__construct();
        $this->transformMapper = $transformMapper;
        $this->exportFactory = $exportFactory;
        $this->patientService = $patientService;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'export:transform-patients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Transform patient from dataset to another by files";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $in = $this->input->getOption(self::OPTION_IN);
        $out = $this->input->getOption(self::OPTION_OUT);
        $map = $this->input->getOption(self::OPTION_MAP);
        //config
        $jsonConfig = file_get_contents($map);
        $dataConfig = $this->transformMapper->getDataConfigByJson($jsonConfig);
        try {
            //read
            $this->output->writeln(" >> Initialize file reader on: " . $in);
            $reader = $this->exportFactory->getReader($dataConfig->convertTypes->source);
            $patientsData = $reader->readFileByPath($in);

            //transform patients
            $this->output->writeln(" >> Transform patient data by json config file: " . $map);
            $data = $this->patientService->transformData($patientsData, $dataConfig);

            //write
            $this->output->writeln(" >> Write a file: " . $out);
            $writer = $this->exportFactory->getWriter($dataConfig->convertTypes->target);
            $writer->saveFileByPath($out, $data);

            $this->output->writeln(' <<<<<<<<<<<>>>>>>>>>>>  Export is done! <<<<<<<<<<<>>>>>>>>>>>');
        } catch (Throwable $exception) {
            dd($exception);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return array(
            array(
                self::OPTION_IN,
                null,
                InputOption::VALUE_OPTIONAL,
                'The input dataset as a file',
                './storage/data/input.csv'
            ),
            array(
                self::OPTION_OUT,
                null,
                InputOption::VALUE_OPTIONAL,
                'The output dataset as a file',
                './storage/data/output-default.csv'
            ),
            array(
                self::OPTION_MAP,
                null,
                InputOption::VALUE_OPTIONAL,
                'Map in/out data as a Json file',
                './storage/data/map.json'
            ),
        );
    }
}
