<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\Image;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetImageTest extends TestCase
{

    public ?GetImage $astrobinWs = null;
    public function setUp(): void
    {
        $this->astrobinWs = new GetImage(null, null);
    }

    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     */
    public function testWebservice(): void
    {
        $response = $this->astrobinWs->getById('8p7u7d');
        $this->assertInstanceOf(AstrobinError::class, $response);
    }


    /**
     * @throws \ReflectionException
     */
    public function testEndpoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);
        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetImage::END_POINT, $endPoint);
    }

    public function testClassResponse(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getObjectEntity');
        $method->setAccessible(true);
        $response = $method->invoke($this->astrobinWs);
        $this->assertEquals(Image::class, $response);
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
    }
}
