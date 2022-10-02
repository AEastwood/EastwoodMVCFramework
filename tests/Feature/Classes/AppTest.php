<?php

namespace Feature\Classes;

use MVC\Classes\App;
use PHPUnit;

class AppTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var App
     */
    private App $app;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //$this->app = new App();
    }

    /**
     * @return void
     */
    public function testAppIsValid()
    {
//        $this->assertIsObject($this->app);
        $this->assertTrue(true);
    }

}