<?php

namespace Unit\Router;

use Monolog\Logger;
use MVC\Classes\Routes\Router;
use PHPUnit;

class RouterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Router
     */
    private Router $router;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->router = new Router();
    }

    /**
     * Test a router can be created
     *
     * @return void
     */
    public function testRouterCanBeCreated(): void
    {
        $this->assertTrue($this->router instanceof Router);
    }

    /**
     * Test router can create and see new route
     *
     * @return void
     * @throws \Exception
     */
    public function testRouterCanAcceptANewRoute(): void
    {
        $this->router->addRoute(['GET', 'HEAD'], '/', function () {
            echo "hello";
        });

        $this->assertNotEmpty($this->router->routes);
    }

    /**
     * test router has valid logger
     *
     * @return void
     */
    public function testRouterHasValidLogger(): void
    {
        $this->assertTrue($this->router->logger instanceof Logger);
    }

    /**
     * Test the router is empty upon creation
     *
     * @return void
     */
    public function testRouterIsEmptyUponCreation(): void
    {
        $this->assertTrue(empty($this->router->routes));
    }

    /**
     * Test router only runs the correct callback for the correct url and methods
     *
     * @return void
     * @throws \Exception
     */
    public function testRouterCanAddMultipleDifferentMethodTypes(): void
    {
        $this->router->addRoute(['GET', 'HEAD'], '/', function () {
            echo "hello";
        });

        $this->router->addRoute(['POST'], '/post', function () {
            echo "post";
        });

        $this->assertCount(1, $this->router->routes['GET, HEAD']);
        $this->assertCount(1, $this->router->routes['POST']);
        $this->assertCount(2, $this->router->routes);
    }

}
