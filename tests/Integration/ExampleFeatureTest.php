<?php

namespace Tests\Integration;

use Tests\FeatureTestCase;

class ExampleFeatureTest extends FeatureTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');
        $this->assertEquals($this->app->version(), $this->response->getContent());
    }
}
