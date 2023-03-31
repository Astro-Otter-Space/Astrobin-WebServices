<?php

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Image;
use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

class GetImageTest extends TestCase
{
    public ?GetImage $astrobinWs = null;

    public function setUp(): void
    {
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetImage($astrobinKey, $astrobinSecret);
    }

    public function testInstance()
    {

    }

    /**
     * @throws WsResponseException
     * @throws WsException
     * @throws JsonException
     */
    public function testGetImageById(): void
    {
        $imageId = '8p7u7d';
        $response = $this->astrobinWs->getById($imageId);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(Image::class, $response);
        $this->assertNotEmpty($response->title);
        $this->assertNotEmpty($response->url_hd);
        $this->assertNotEmpty($response->url_regular);
        $this->assertNotEmpty($response->url_thumb);
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
    }
}