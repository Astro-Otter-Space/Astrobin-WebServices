<?php

namespace Services;

use AstrobinWs\GuzzleSingleton;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class GuzzleSingletonTest extends TestCase
{

    public function testGetInstance(): void
    {
        $guzzleSingleton = GuzzleSingleton::getInstance();
        $this->assertInstanceOf(Client::class, $guzzleSingleton);
    }

    public function testUrlApi(): void
    {
        $guzzleSingleton = GuzzleSingleton::getInstance();
        $this->assertEquals( 'https://www.astrobin.com', $guzzleSingleton->getConfig('base_uri'));
    }

}
