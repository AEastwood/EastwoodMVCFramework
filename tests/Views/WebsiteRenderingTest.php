<?php

namespace Views;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit;

class WebsiteRenderingTest extends PHPUnit\Framework\TestCase
{

    private Client $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'https://adameastwood.com',
            'timeout' => 2.0,
        ]);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testIndexPageCanBeRenderedSuccessfully()
    {
        $request = $this->client->get('/');

        $statusCode = $request->getStatusCode();

        $this->assertSame(200, $statusCode, "Website Status: {$statusCode}");
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testUploadPageCanBeRenderedSuccessfully()
    {
        $this->
        $request = $this->client->get('/upload');

        $statusCode = $request->getStatusCode();

        $this->assertSame(200, $statusCode, "Website Status: {$statusCode}");
    }

}