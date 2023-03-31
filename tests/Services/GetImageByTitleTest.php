<?php

namespace Services;

use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetImageByTitleTest extends TestCase
{
    public ?GetImage $astrobinWs = null;

    public function setUp(): void
    {
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetImage($astrobinKey, $astrobinSecret);
    }

    public function testGetImagesByTitleBadLimit(): void
    {
        $badLimite = 9999999999;
        $subject = 'm42';
        $nullResponse = $this->astrobinWs->getImagesByTitle($subject, $badLimite);
        $this->assertNull($nullResponse);
    }

    public function testGetImagesByTitleMultipleLimit(): void
    {
        $subject = 'm51';
        $limit = random_int(2, 6);
        $response = $this->astrobinWs->getImagesByTitle($subject, $limit);
        $this->assertInstanceOf(ListImages::class, $response);
        $this->assertEquals($limit, $response->count);
    }

    public function testGetImagesByTitle(): void
    {
        $subject = 'm51';
        $response = $this->astrobinWs->getImagesByTitle($subject, 1);
        $this->assertInstanceOf(Image::class, $response);
        $this->assertStringContainsString($subject, strtolower($response->title));

        $subject = 'medusa';
        $response = $this->astrobinWs->getImagesByTitle($subject, 1);
        $this->assertStringContainsString($subject, strtolower($response->title));
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
    }
}
