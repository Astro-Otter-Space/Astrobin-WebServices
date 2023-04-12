<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\Collection;
use AstrobinWs\Response\DTO\Image;
use AstrobinWs\Response\DTO\ListCollection;
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

    /**
     * @throws \ReflectionException
     */
    public function testGetCollectionEntity(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getCollectionEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(ListCollection::class, $response);
    }

    /**
     * @throws WsResponseException
     * @throws \ReflectionException
     * @throws WsException
     * @throws \JsonException
     */
    public function testNullableKeys(): void
    {
        $badResponse = $this->badAstrobinWs->getById('1');
        $this->assertInstanceOf(AstrobinError::class, $badResponse);
        $this->assertEquals(WsException::KEYS_ERROR, $badResponse->getMessage());
    }

    /**
     * @throws \ReflectionException
     * @throws \JsonException
     * @throws WsResponseException
     */
    public function testGetById(): void
    {
        $this->expectException(WsException::class);
        $this->expectExceptionCode(500);
        $this->astrobinWs->getById(null);

        $response = $this->astrobinWs->getById('a');
        $this->assertInstanceOf(AstrobinError::class, $response);

        $reponse = $this->astrobinWs->getById('1');
        $this->assertInstanceOf(Collection::class, $reponse);
    }

//    public function testGetListCollectionByUser(): void
//    {
//        $nullCollection = $this->astrobinWs->getListCollectionByUser(null);
//    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
