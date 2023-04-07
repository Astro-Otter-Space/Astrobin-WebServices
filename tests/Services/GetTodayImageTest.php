<?php

namespace Services;

use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListToday;
use AstrobinWs\Response\Today;
use AstrobinWs\Services\GetImage;
use AstrobinWs\Services\GetTodayImage;
use PHPUnit\Framework\TestCase;

class GetTodayImageTest extends TestCase
{

    public ?GetTodayImage $astrobinWs = null;
    public ?GetTodayImage $badAstrobinWs = null;

    public function setUp(): void
    {
        $this->badAstrobinWs = new GetTodayImage(null, null);
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetTodayImage($astrobinKey, $astrobinSecret);
    }

    public function testGetEndPoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);
        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetTodayImage::END_POINT, $endPoint);
    }


    public function testGetObjectEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getObjectEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(Today::class, $response);
    }


    public function testGetCollectionEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getCollectionEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(ListToday::class, $response);
    }
//
//    public function testGetDayImage()
//    {
//
//    }

    public function testGetTodayImage(): void
    {
        $today = new \DateTime('now');
        $response = $this->astrobinWs->getTodayImage();
        $this->assertInstanceOf(Today::class, $response);
        $this->assertEquals($today->format('Y-m-d'), $response->date);
        $this->assertInstanceOf(Image::class, $response->getIterator()->current());
    }

    public function testGetById(): void
    {
        $response = $this->astrobinWs->getById(null);
        $this->assertNull($response);
        $response = $this->astrobinWs->getById('1');
        $this->assertNull($response);
    }
}
