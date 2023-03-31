<?php

namespace Webservice;

use AstrobinWs\GuzzleSingleton;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class GuzzleSingletonTest extends TestCase
{

    public function testInstance(): void
    {
        $singleton = GuzzleSingleton::getInstance();
        $reflection = new \ReflectionClass($singleton);
        $instance = $reflection->getProperty('_instance');
        $instance->setAccessible(true); // now we can modify that :)
        $instance->setValue(null, null); // instance is gone
        $instance->setAccessible(false);

        $singleton = GuzzleSingleton::getInstance();
        $this->assertInstanceOf(Client::class, $singleton);
    }
}