<?php

namespace Services;

use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\Collection;
use AstrobinWs\Response\DTO\Image;
use AstrobinWs\Services\GetCollection;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetCollectionTest extends TestCase
{
    public ?GetCollection $astrobinWs = null;

    public ?GetCollection $badAstrobinWs = null;

    public function setUp(): void
    {
        $this->badAstrobinWs = new GetCollection(null, null);
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetCollection($astrobinKey, $astrobinSecret);
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetEndPoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);
        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetCollection::END_POINT, $endPoint);
    }

    public function testGetObjectEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getObjectEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(Collection::class, $response);
    }

    public function testNullableKey(): void
    {
        $badResponse = $this->badAstrobinWs->getById(1);
        $this->assertInstanceOf(AstrobinError::class, $badResponse);
    }

//    public function testGetListCollectionByUser(): void
//    {
//        $nullCollection = $this->astrobinWs->getListCollectionByUser(null);
//    }

//    public function testGetById()
//    {
//
//    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
