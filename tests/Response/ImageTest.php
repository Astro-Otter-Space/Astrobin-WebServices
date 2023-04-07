<?php

namespace Response;

use AstrobinWs\Response\DTO\Image;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;
use function responses\sort;

class ImageTest extends TestCase
{
    public Image $image;

    public static array $expectedProperties = [
        'description',
        'subjects',
        'title',
        'uploaded',
        'url_gallery',
        'url_hd',
        'url_histogram',
        'url_regular',
        'url_skyplot',
        'url_thumb',
        'user'
    ];

    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testProperties(): void
    {
        $reflexion = new \ReflectionClass((new Image()));
        $props = array_map(static fn(\ReflectionProperty $prop) => $prop->getName(), $reflexion->getProperties());
        sort($props);
        $this->assertEquals($props, self::$expectedProperties);
    }
}