<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\Image;
use AstrobinWs\Response\DTO\ListToday;
use AstrobinWs\Response\DTO\Today;
use AstrobinWs\Services\GetTodayImage;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

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

    public function testNullableKeys(): void
    {
        $response = $this->badAstrobinWs->getTodayImage();
        $this->assertInstanceOf(AstrobinError::class, $response);
        $this->assertEquals(WsException::KEYS_ERROR, $response->getMessage());
    }

    /**
     * @throws WsResponseException
     * @throws ReflectionException
     * @throws WsException
     * @throws JsonException
     * @throws Exception
     */
    public function testGetDayImage(): void
    {
        /**
         * Test with limit random and offset at 0
         */
        $limit = random_int(2, 6);
        $now = new \DateTime('now');
        $startDay = clone $now;
        $startDay = $startDay->sub(New \DateInterval(sprintf('P%sD', $limit-1)));
        $interval = new \DateInterval('P1D');

        $listDates = array_map(static function(\DateTime $date) {
            return $date->format('Y-m-d');
        }, iterator_to_array((new \DatePeriod($startDay, $interval, $limit-1))->getIterator()));

        $response = $this->astrobinWs->getDayImage(0, $limit);
        $this->assertInstanceOf(ListToday::class, $response);
        $this->assertCount($limit, $response->listToday);
        /** @var Today $today */
        while ($response->getIterator()->valid()) {
            $today = $response->getIterator()->current();
            $this->assertInstanceOf(Today::class, $today);
            $this->assertContains($today->date, $listDates);
            $response->getIterator()->next();
        }

        /**
         * Test with offset and limit null
         */
        $response = $this->astrobinWs->getDayImage(null, null);
        $this->assertInstanceOf(Today::class, $response);
        $this->assertEquals($response->date, (new \DateTime())->format('Y-m-d'));

        /**
         * Test if Today is null
         */
        $emptyResponse = $this->astrobinWs->getDayImage(-1, -5);
        $this->assertInstanceOf(AstrobinError::class, $emptyResponse);
    }

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
