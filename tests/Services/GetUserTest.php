<?php

namespace Services;

use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\User;
use AstrobinWs\Services\GetImage;
use AstrobinWs\Services\GetUser;
use PHPUnit\Framework\TestCase;

class GetUserTest extends TestCase
{

    public ?GetUser $astrobinWs = null;
    public ?GetUser $badAstrobinWs = null;

    public function setUp(): void
    {
        $this->badAstrobinWs = new GetUser(null, null);
        $astrobinKey = getenv('ASTROBIN_API_KEY');
        $astrobinSecret = getenv('ASTROBIN_API_SECRET');
        $this->astrobinWs = new GetUser($astrobinKey, $astrobinSecret);
    }

    public function testGetEndPoint(): void
    {
        $reflection = new \ReflectionClass($this->astrobinWs);
        $method = $reflection->getMethod('getEndPoint');
        $method->setAccessible(true);
        $endPoint = $method->invoke($this->astrobinWs);
        $this->assertEquals(GetUser::END_POINT, $endPoint);
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
        $this->assertEquals(User::class, $response);
    }

    public function testGetById(): void
    {
        $this->expectException(WsResponseException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getById(null);

        $imageId = 'HelloHereBadId';
        $badResponse = $this->astrobinWs->getById($imageId);
        $this->assertInstanceOf(AstrobinError::class, $badResponse);

        $userId = 'siovene';
        $response = $this->astrobinWs->getById($userId);
        var_dump($response);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(User::class, $response);
    }

    public function testGetByUsername(): void
    {
        $user = 'siovene';
        $response = $this->astrobinWs->getByUsername($user);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($user, $response->username);
    }

    public function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
