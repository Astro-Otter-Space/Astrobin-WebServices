<?php

namespace Response\DTO\Item;

use AstrobinWs\Response\DTO\Item\Image;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public static array $expectedProperties = [
        'description',
        'likes',
        'subjects',
        'title',
        'uploaded',
        'url_advanced_skyplot',
        'url_advanced_skyplot_small',
        'url_gallery',
        'url_hd',
        'url_histogram',
        'url_regular',
        'url_skyplot',
        'url_solution',
        'url_advanced_solution',
        'url_thumb',
        'user',
        'views'
    ];

    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testPropertiesExist(): void
    {
        $reflexion = new \ReflectionClass((new Image()));
        $props = array_map(static fn(\ReflectionProperty $prop) => $prop->getName(), $reflexion->getProperties());
        sort($props);
        $this->assertEquals(self::$expectedProperties, $props);
    }
}

