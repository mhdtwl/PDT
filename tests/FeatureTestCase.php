<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Laravel\Lumen\Application;

abstract class FeatureTestCase extends TestCase
{

    /**
     * @var Authenticatable
     */
    protected $user;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        //we need this in order to use PHPUnit data providers
        $this->refreshApplication();
    }

    protected function setUp(): void
    {
        $this->refreshApplication();

        parent::setUp();
    }
}
