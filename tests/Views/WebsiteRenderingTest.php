<?php

namespace Views;

use GuzzleHttp\Client;
use PHPUnit;

class WebsiteRenderingTest extends PHPUnit\Framework\TestCase
{

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testWebsiteRendersSuccessfully()
    {
        $client = new Client([
            'base_uri' => 'https://adameastwood.com',
            'timeout' => 2.0,
        ]);

        $request = $client->get('/');

        $statusCode = $request->getStatusCode();

        $this->assertSame(200, $statusCode, "Website Status: {$statusCode}");
    }

}