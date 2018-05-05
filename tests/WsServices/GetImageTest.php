<?php
use Astrobin\Services\GetImage;

/**
 * Class GetImageTest
 */
class GetImageTest extends PHPUnit_Framework_TestCase
{

    /** @var GetImage */
    private $client;

    CONST FAKE_KEY = '3524e6ee81749ea19a1ed0f14c5390efb4ac578f';

    CONST FAKE_SECRET = '6f0a67f7aeb93cbce4addec000fca9991876df63';

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = new GetImage(self::FAKE_KEY, self::FAKE_SECRET);
    }

//    /**
//     * Test Call API without Token
//     *
//     * @throws ReflectionException
//     * @throws \Astrobin\Exceptions\WsException
//     * @throws \Astrobin\Exceptions\WsResponseException
//     */
//    public function testGetImageByIdWithoutToken()
//    {
//        $id = rand(1, 1000);
//        $response = $client->getImageById($id);
//
//        $this->assertInstanceOf(\Astrobin\Exceptions\WsException::class, $response,  '');
//    }


    /**
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageById()
    {
        $id = 341955;
        $response = $this->client->getImageById($id);

        $this->assertInstanceOf(\Astrobin\Response\Image::class, $response);
        $this->assertClassHasAttribute('title', \Astrobin\Response\Image::class);
    }


    /**
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageWithBadId()
    {
        $response = $this->client->getImageById(null);
        $this->expectException(\Astrobin\Exceptions\WsResponseException::class);

        $fakeId = md5(new DateTime('now'));
        $response = $this->$this->client->getImageById($fakeId);
        $this->expectException(\Astrobin\Exceptions\WsResponseException::class);
    }

}
