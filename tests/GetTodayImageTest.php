<?php

use AstrobinWs\Response\Image;
use AstrobinWs\Response\Today;
use AstrobinWs\Services\GetTodayImage;
use PHPUnit\Framework\TestCase;

/**
 * Class GetTodayImageTest
 */
class GetTodayImageTest extends TestCase
{
    /** @var GetTodayImage */
    private $client;


    public function setUp(): void
    {
        parent::setUp();
        $this->client = null; // new GetTodayImage();
    }

    public function testGetDayImage()
    {
        $now = new DateTime('now');
        $response = $this->client->getTodayDayImage();
//        $this->assertInstanceOf(Today::class, $response, __METHOD__ . ' : instance of ' . get_class($response) . ' OK');
//        $this->assertEquals($response->date, $now->format('Y-m-d'), __METHOD__ . ' : day returned and today are equals, OK');
//
//        foreach ($response->getIterator() as $respImage) {
//            $this->assertInstanceOf(Image::class, $respImage, __METHOD__ . ' : instance of ' . get_class($respImage) . ' OK');
//        }
    }

}
