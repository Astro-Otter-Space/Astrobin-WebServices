<?php

namespace Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Image;
use AstrobinWs\Response\DTO\ListImages;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetImageTest extends TestCase
{

    public ?GetImage $astrobinWs = null;
    public ?GetImage $badAstrobinWs = null;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->badAstrobinWs = new GetImage(null, null);
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetImage($astrobinKey, $astrobinSecret);
    }

    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     */
    public function testNullableKeys(): void
    {
        $response = $this->badAstrobinWs->getById('8p7u7d');
        $this->assertInstanceOf(AstrobinError::class, $response);
        $this->assertEquals(WsException::KEYS_ERROR, $response->getMessage());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testGetEndPoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);
        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetImage::END_POINT, $endPoint);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testGetObjectEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getObjectEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(Image::class, $response);
    }

    /**
     * @throws WsException
     * @throws \JsonException
     */
    public function testGetById(): void
    {
        /**
         * Test id Null
         */
        $this->expectException(WsResponseException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getById(null);

        /**
         * Test with bad id
         */
        $imageId = 'HelloHereBadId';
        $badResponse = $this->astrobinWs->getById($imageId);
        $this->assertInstanceOf(AstrobinError::class, $badResponse);

        /**
         * Classic test
         */
        $imageId = '8p7u7d';
        $response = $this->astrobinWs->getById($imageId);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(Image::class, $response);
        $this->assertNotEmpty($response->title);
        $this->assertNotEmpty($response->url_hd);
        $this->assertNotEmpty($response->url_regular);
        $this->assertNotEmpty($response->url_thumb);
    }

    /**
     */
    public function testGetImagesByUser(): void
    {
        $username = 'FalseUser';
        $badLimit = 9999999999;

        /**
         * Tests limits
         */
       $response =  $this->astrobinWs->getImagesByUser($username, $badLimit);
       $this->assertNull($response);

       /**
        * test unknown user
        */
       $limit = random_int(2, 6);
       $response = $this->astrobinWs->getImagesByUser($username, $limit);
       $this->assertEquals(0, $response->count);

       /**
        * Test known user
        */
        $username = 'siovene';
        $limit = random_int(2, 6);
        /** @var ListImages $response */
        $response = $this->astrobinWs->getImagesByUser($username, $limit);
        $this->assertLessThanOrEqual($limit, $response->count);
        $respIterator = $response->getIterator();
        while ($respIterator->valid()) {
            $response = $respIterator->current();
            $this->assertInstanceOf(Image::class, $response);
            $this->assertStringContainsString($username, $response->user);
            $respIterator->next();
        }
    }

    public function testGetImagesByDescription(): void
    {
        /**
         * test bad limit
         */
        $description = "Andromeda galaxy";
        $badLimit = 9999999;
        $badLimitResponse = $this->astrobinWs->getImagesByDescription($description, $badLimit);
        $this->assertNull($badLimitResponse);

        /**
         * Test description
         */
        $limit = random_int(2, 6);
        $response = $this->astrobinWs->getImagesByDescription($description, $limit);
        $this->assertLessThanOrEqual($limit, $response->count);
        $respIterator = $response->getIterator();
        while($respIterator->valid()) {
            $response = $respIterator->current();
            $this->assertInstanceOf(Image::class, $response);
            $this->assertStringContainsString(strtolower($description), strtolower($response->description));
            $respIterator->next();
        }
    }

    /**
     * @throws \Exception
     */
    public function testGetImagesByTitle(): void
    {
        /**
         * Bad limit
         */
        $badLimite = 9999999999;
        $subject = 'm42';
        $nullResponse = $this->astrobinWs->getImagesByTitle($subject, $badLimite);
        $this->assertNull($nullResponse);

        /**
         * Multiple limit
         */
        $subject = 'm51';
        $limit = random_int(2, 6);
        $response = $this->astrobinWs->getImagesByTitle($subject, $limit);
        $this->assertInstanceOf(ListImages::class, $response);
        $this->assertEquals($limit, $response->count);

        /**
         * Test good parameters
         */
        $subject = 'm51';
        $response = $this->astrobinWs->getImagesByTitle($subject, 1);
        $this->assertInstanceOf(Image::class, $response);
        $this->assertStringContainsString($subject, strtolower($response->title));

        $subject = 'medusa';
        $response = $this->astrobinWs->getImagesByTitle($subject, 1);
        $this->assertStringContainsString($subject, strtolower($response->title));
    }

    /**
     * @throws \ReflectionException
     * @throws WsResponseException
     * @throws WsException
     */
    public function testGetImagesByRangeDate(): void
    {
        /**
         * Tests good date
         */
        $dateFromStr = '2023-01-01';
        $dateToStr = '2023-06-30';
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, $dateToStr, null);
        $this->assertInstanceOf(ListImages::class, $response);
        $this->assertLessThanOrEqual(AbstractWebService::LIMIT_MAX, $response->count);

        $overLimit = AbstractWebService::LIMIT_MAX+1000000;
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, $dateToStr, $overLimit);
        $this->assertNull($response);

        /**
         * Test with date null
         */
        $dateToStr = null;
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, $dateToStr, null);
        $this->assertInstanceOf(ListImages::class, $response);

        /**
         * Test format date $dateFromStr
         */
        $dateFromStr = '2023-04-aa';
        $dateToStr = 'gfg56fdsg5';

        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, $dateToStr, null);

        /**
         * Test format date $dateFromStr wither other format
         */
        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getImagesByRangeDate('now', null, null);

        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getImagesByRangeDate('+1 day', '2023-06-30', null);

        $dateFromStr = (new \DateTime)->format('d M Y H:i:s');
        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, null, null);

        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getImagesByRangeDate($dateFromStr, '2023-06-30', null);
    }

    /**
     * @throws \Exception
     */
    public function testGetImagesBySubject(): void
    {
        /**
         * Test limit too big
         */
        $badLimite = 9999999999;
        $subject = 'm42';
        $nullResponse = $this->astrobinWs->getImagesBySubject($subject, $badLimite);
        $this->assertNull($nullResponse);

        /**
         * Test multiplte response
         */
        $subject = 'm31';
        $limit = random_int(2, 6);
        $response = $this->astrobinWs->getImagesBySubject($subject, $limit);
        $this->assertInstanceOf(ListImages::class, $response);
        $this->assertEquals($limit, $response->count);

        /**
         * Test good parameters
         */
        $subject = 'M42';
        $goodResponse = $this->astrobinWs->getImagesBySubject($subject, 1);
        $this->assertInstanceOf(Image::class, $goodResponse);
    }

    public function testGetImageBy(): void
    {
        $response = $this->astrobinWs->getImageBy([], 9999);
        $this->assertNull($response);

        $response = $this->astrobinWs->getImageBy([], 5);
        $this->assertCount(5, $response);
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
