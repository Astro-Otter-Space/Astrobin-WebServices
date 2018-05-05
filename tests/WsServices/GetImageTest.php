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


    /**
     * Test by ID OK
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageById()
    {
        $id = 341955;
        $response = $this->client->getImageById($id);

        $this->assertInstanceOf(\Astrobin\Response\Image::class, $response, __METHOD__ . ' : response Image OK');
        $this->assertClassHasAttribute('title', \Astrobin\Response\Image::class, __METHOD__ . ': attribute title OK');
    }


    /**
     * Test with null Id
     * @expectedException  \Astrobin\Exceptions\WsResponseException
     * @expectedExceptionMessage
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageWithNullId()
    {
        $response = $this->client->getImageById(null);
        $this->expectExceptionMessage("[Astrobin response] '' is not a correct value, integer expected");
    }



    /**
     * Test bith Bad Id
     *
     * @expectedException \Astrobin\Exceptions\WsResponseException
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageWithBadId()
    {
        $fakeId = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $response = $this->client->getImageById($fakeId);
        $this->expectExceptionMessage("[Astrobin response] \'$fakeId\' is not a correct value, integer expected");
    }


    /**
     * Test by subjects
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImagesBySubject()
    {
        $length = rand(1, 15);
        $random_number_array = range(1, 110);
        shuffle($random_number_array );
        $random_number_array = array_slice($random_number_array ,0,$length);

        $subjects = array_map(function($value) {
            return implode('', ['m', $value]);
        }, $random_number_array);

        foreach ($subjects as $subject) {
            $limit = rand(1, 5);
            $response = $this->client->getImagesBySubject($subject, $limit);

            $instance = ($limit > 1) ? \Astrobin\Response\ListImages::class : \Astrobin\Response\Image::class;
            $this->assertInstanceOf($instance, $response, __METHOD__  . " : response $instance OK");
            if (is_a($response, \Astrobin\Response\ListImages::class)) {
                // Test images
                foreach ($response->getIterator() as $respImage) {
                    $this->assertInstanceOf(\Astrobin\Response\Image::class, $respImage, __METHOD__ . ' : check if instance Image OK');
                    $this->assertContains(strtolower($subject), strtolower($respImage->title), __METHOD__ . " : response title is '$respImage->title', expected $subject inside, OK");
                }

            } else if (is_a($response, \Astrobin\Response\Image::class)) {
                $this->assertInstanceOf(\Astrobin\Response\Image::class, $response, __METHOD__ . ' : check if instance Image OK');
                $this->assertContains(strtolower($subject), strtolower($response->title),__METHOD__ . " : response title contained '$response->title'', expected $subject inside, OK");
            }
        }
    }


    /**
     * @expectedException \Astrobin\Exceptions\WsResponseException
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImagesBySubjectNotFound()
    {
        $fakeSubject = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $response = $this->client->getImagesBySubject($fakeSubject, rand(1, 5));
    }

    public function testGetImagesByDescription()
    {



    }


    /**
     * Test by username
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImageByUser()
    {
        $listUser = ['gorann', 'protoplot', 'tlewis', 'Mark_Hudson', 'SparkyHT'];
        $user = $listUser[array_rand($listUser, 1)];
        $response = $this->client->getImagesByUser($user, 5);

        $this->assertInstanceOf(\Astrobin\Response\ListImages::class, $response, __METHOD__ . ' : ListImages returned OK');
        foreach ($response->getIterator() as $resp) {
            $this->assertEquals($user, $resp->user, __METHOD__ . ' : ' . $user . ' selected is equale to ' . $resp->user);
        }
    }


    /**
     * Test with fake user
     * @expectedException \Astrobin\Exceptions\WsResponseException
     *
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImagesByBadUser()
    {
        $fakeUser = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $response = $this->client->getImagesByUser($fakeUser, 5);
    }


    /**
     * @throws ReflectionException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     */
    public function testGetImagesByRangeDate()
    {
        $dateTo = new DateTime('now');
        $dateFrom = clone $dateTo;
        $dateFrom->sub(new DateInterval('P1M'));

        $response = $this->client->getImagesByRangeDate($dateFrom->format('y-m-d'), $dateTo->format('y-m-d'));
        $this->assertInstanceOf(\Astrobin\Response\ListImages::class, $response);
        foreach ($response->getIterator() as $imgResp) {
            $this->assertLessThanOrEqual($dateFrom->getTimestamp(), $imgResp->c);
            $this->assertGreaterThanOrEqual($dateTo->getTimestamp());
        }
    }

    public function testGetImagesByRangeDateFalse()
    {

    }
}
