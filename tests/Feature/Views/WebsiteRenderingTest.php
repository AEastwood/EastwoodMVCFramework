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
            'base_uri' => 'https://adameastwood.com/',
            'timeout' => 2.0,
        ]);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testIndexPageCanBeRenderedSuccessfully(): void
    {
        $request = $this->client->get('/');

        $crawler = new Crawler($request->getBody()->getContents());

        $this->assertSame(200, $request->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div#main-content')->count());
        $this->assertEquals(1, $crawler->filter('div#main-content>img')->count());
    }

}