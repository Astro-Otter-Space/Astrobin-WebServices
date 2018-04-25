<?php

use Astrobin\Services\GetImage;


/**
 * Class GetImageTest
 */
class GetImageTest extends PHPUnit_Framework_TestCase
{

    /** @var GetImage */
    protected $client;

    /**
     *
     */
    public function setUp()
    {
       $this->client = new GetImage('3524e6ee81749ea19a1ed0f14c5390efb4ac578f', '6f0a67f7aeb93cbce4addec000fca9991876df63');
    }


    /**
     * @throws ReflectionException
     * @throws Astrobin\Exceptions\WsException
     */
    public function testGetImageById()
    {
        $imageId = 335910;
        $response = $this->client->getImageById($imageId);

        $this->assertInstanceOf(\Astrobin\Response\Image::class, get_class($response));
        $this->assertEquals('https://www.astrobin.com/' . $imageId . '/0/rawthumb/gallery/', $response->url_gallery);
    }


    /**
     * @throws ReflectionException
     */
    public function testGetImageBySubject()
    {
        $messier = 'm' . rand(1,110);
        $response = $this->client->getImagesBySubject($messier, 1);

        $this->assertEquals(\Astrobin\Response\Image::class, get_class($response));

    }


    public function testGetImageBySubjectExceedLimit()
    {

    }

}
