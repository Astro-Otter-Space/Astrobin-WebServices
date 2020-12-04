<?php

use AstrobinWs\Response\Image;
use AstrobinWs\Response\Today;
use AstrobinWs\Services\GetTodayImage;

class GetTodayImageTest extends \PHPUnit\Framework\TestCase
{
    /** @var GetTodayImage */
    private $client;

    public const FAKE_KEY = '';

    public const FAKE_SECRET = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->client = new GetTodayImage(self::FAKE_KEY, self::FAKE_SECRET);
    }

    public function testGetDayImage()
    {
        $now = new DateTime('now');
        $response = $this->client->getTodayDayImage();
        $this->assertInstanceOf(Today::class, $response, __METHOD__ . ' : instance of ' . get_class($response) . ' OK');
        $this->assertEquals($response->date, $now->format('Y-m-d'), __METHOD__ . ' : day returned and today are equals, OK');

        foreach ($response->getIterator() as $respImage) {
            $this->assertInstanceOf(Image::class, $respImage, __METHOD__ . ' : instance of ' . get_class($respImage) . ' OK');
        }
    }

}
