<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListToday;
use AstrobinWs\Response\Today;
use AstrobinWs\Services\GetImage;
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

    /**
     * @throws WsResponseException
     * @throws ReflectionException
     * @throws WsException
     * @throws JsonException
     * @throws Exception
     */
    public function testGetDayImage(): void
    {
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
        foreach ($response->listToday as $today) {
            $this->assertContains($today->date, $listDates);
        }
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
