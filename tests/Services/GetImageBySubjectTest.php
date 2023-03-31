<?php

namespace Services;

use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetImageBySubjectTest extends TestCase
{
    public ?GetImage $astrobinWs = null;

    public function setUp(): void
    {
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetImage($astrobinKey, $astrobinSecret);
    }

    public function testGetImagesBySubjectWithBadLimit(): void
    {
        $badLimite = 9999999999;
        $subject = 'm42';
        $nullResponse = $this->astrobinWs->getImagesBySubject($subject, $badLimite);
        $this->assertNull($nullResponse);
    }

    /**
     * @throws \Exception
     */
    public function testGetImagesBySubjectsMultipleLimit(): void
    {
        $subject = 'm31';
        $limit = random_int(2, 6);
        $response = $this->astrobinWs->getImagesBySubject($subject, $limit);
        $this->assertInstanceOf(ListImages::class, $response);
        $this->assertEquals($limit, $response->count);

        // Test of iterator is in tests\Response\ListImage
    }

    public function testGetImagesBySubject(): void
    {
        $subject = 'M42';
        $goodResponse = $this->astrobinWs->getImagesBySubject($subject, 1);
        $this->assertInstanceOf(Image::class, $goodResponse);
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
    }
}
