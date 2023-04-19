<?php

namespace Response\DTO\Item;

use AstrobinWs\Response\DTO\Item\Today;
use AstrobinWs\Response\DTO\Item\User;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class TodayTest extends TestCase
{
    public static array $expectedProperties = [
        'date',
        'image',
        'resource_uri'
    ];

    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testPropertiesExist(): void
    {
        $reflexion = new \ReflectionClass((new Today()));
        $props = array_map(static fn(\ReflectionProperty $prop) => $prop->getName(), $reflexion->getProperties());
        sort($props);
        $this->assertEquals(self::$expectedProperties, $props);
    }
}
