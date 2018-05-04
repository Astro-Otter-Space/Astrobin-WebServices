<?php
use Astrobin\Services\GetImage;

/**
 * Class GetImageTest
 */
class GetImageTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test Call API without Token
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageByIdWithoutToken()
    {
        $client = new GetImage(null, null);
        $id = rand(1, 1000);
        $response = $client->getImageById($id);

        $this->assertInstanceOf(\Astrobin\Exceptions\WsException::class, $response,  '');
    }


    public function testGetImageById()
    {
        $id = 341955;
        $client = new GetImage('3524e6ee81749ea19a1ed0f14c5390efb4ac578f', '6f0a67f7aeb93cbce4addec000fca9991876df63');
        $response = $client->getImageById($id);

        $this->assertInstanceOf(\Astrobin\Response\Image::class, $response);
        $this->assertClassHasAttribute('title', \Astrobin\Response\Image::class);
    }

}
