<?php

namespace Feature\Views;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteRenderingTest extends PHPUnit\Framework\TestCase
{

    private Client $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'https://staging.adameastwood.com',
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

        $crawler = new Crawler($request->getBody()->getContents());

        $statusCode = $request->getStatusCode();

        $this->assertSame(200, $statusCode, "Website Status: {$statusCode}");
        $this->assertEquals(1, $crawler->filter('div#main-content')->count());
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testErrorPage()
    {
        $request = $this->client->get('/random-non-existent-page', ['http_errors' => false]);
        $statusCode = $request->getStatusCode();
        $this->assertSame(404, $statusCode, "Error Page Status: {$statusCode}");
    }

}