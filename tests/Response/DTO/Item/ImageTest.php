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
        'url_advanced_solution',
        'url_gallery',
        'url_hd',
        'url_histogram',
        'url_regular',
        'url_skyplot',
        'url_solution',
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
        $props = array_map(static fn(\ReflectionProperty $prop): string => $prop->getName(), $reflexion->getProperties());
        sort($props);
        $this->assertEquals(self::$expectedProperties, $props);
    }

    public function testUploadedPropertyIsNull(): void
    {
        $image = new Image();
        $this->assertNull($image->getUploaded());
    }

    public function testUploadedProperty(): void
    {
        $image = new Image();
        $image->uploaded = '2022-09-22T11:20:22.584072';
        $this->assertInstanceOf(\DateTime::class, $image->getUploaded());
    }

    public function testBadUploadedProperty(): void
    {
        $image = new Image();
        $image->uploaded = '20aa-9-22T11:22.584072';
        $this->assertNull($image->getUploaded());
    }
}

