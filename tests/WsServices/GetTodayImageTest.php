<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 05/05/18
 * Time: 18:04
 */

use AstrobinWs\Services\GetTodayImage;

class GetTodayImageTest extends \PHPUnit\Framework\TestCase
{
    /** @var GetTodayImage */
    private $client;

    const FAKE_KEY = '';

    const FAKE_SECRET = '';

    public function setUp()
    {
        parent::setUp();
        $this->client = new GetTodayImage(self::FAKE_KEY, self::FAKE_SECRET);
    }

    public function testGetDayImage()
    {
        $now = new DateTime('now');
        $response = $this->client->getTodayDayImage();
        $this->assertInstanceOf(\AstrobinWs\Response\Today::class, $response, __METHOD__ . ' : instance of ' . get_class($response) . ' OK');
        $this->assertEquals($response->date, $now->format('Y-m-d'), __METHOD__ . ' : day returned and today are equals, OK');

        foreach ($response->getIterator() as $respImage) {
            $this->assertInstanceOf(\AstrobinWs\Response\Image::class, $respImage, __METHOD__ . ' : instance of ' . get_class($respImage) . ' OK');
        }
    }

}
