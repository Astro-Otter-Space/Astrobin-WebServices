<?php

namespace Response\DTO;

use AstrobinWs\Response\DTO\Item\User;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public static array $expectedProperties = [
        'about',
        'avatar',
        'hobbies',
        'id',
        'image_count',
        'job',
        'language',
        'username',
        'website'
    ];
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testPropertiesExist(): void
    {
        $reflexion = new \ReflectionClass((new User()));
        $props = array_map(static fn(\ReflectionProperty $prop) => $prop->getName(), $reflexion->getProperties());
        sort($props);
        $this->assertEquals(self::$expectedProperties, $props);
    }
}
