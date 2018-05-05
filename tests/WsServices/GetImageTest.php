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
     *
     */
    public function testGetImageWithNullId()
    {
        try {
            $this->client->getImageById(null);
        } catch (Exception $e) {
            $this->assertInstanceOf(\Astrobin\Exceptions\WsResponseException::class, $e);
//            $this->expectException('\Astrobin\Exceptions\WsResponseException');
        }
    }

    /**
     */
    public function testGetImageWithBadId()
    {
        try {
            $fakeId = md5(new DateTime('now'));
            $this->client->getImageById($fakeId);
        } catch (Exception $e) {
            $this->assertInstanceOf(\Astrobin\Exceptions\WsResponseException::class, $e);
        }
    }


    public function testGetImageByUser()
    {
        $user = array_rand(['gorann', 'protoplot', 'tlewis', 'Mark_Hudson', 'SparkyHT'], 1);
        $response = $this->client->getImagesByUser($user, 5);

        $this->assertInstanceOf(\Astrobin\Response\Image::class, $response);
        $this->assertEquals($user, $response->user);

    }
}
