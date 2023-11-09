<?php

namespace Services;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Item\User;
use AstrobinWs\Services\GetUser;
use Exception;
use PHPUnit\Framework\TestCase;

class GetUserTest extends TestCase
{

    public ?GetUser $astrobinWs = null;

    public ?GetUser $badAstrobinWs = null;

    protected function setUp(): void
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

    public function testNullableKeys(): void
    {
        $response = $this->badAstrobinWs->getById('1');
        $this->assertInstanceOf(AstrobinError::class, $response);
        $this->assertEquals(WsException::KEYS_ERROR, $response->getMessage());
    }

    /**
     * @throws \JsonException|WsResponseException
     * @throws Exception
     */
    public function testGetById(): void
    {
        $imageId = 'HelloHereBadId';
        $badResponse = $this->astrobinWs->getById($imageId);
        $this->assertInstanceOf(AstrobinError::class, $badResponse);

        $userId = '9999999999999999999999999';
        $response = $this->astrobinWs->getById($userId);
        $this->assertInstanceOf(AstrobinError::class, $response);

        $this->expectException(WsResponseException::class);
        $this->expectExceptionCode(500);
        $response = $this->astrobinWs->getById(null);

        $userId = (string)random_int(1, 9999);
        $response = $this->astrobinWs->getById($userId);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($userId, $response->id);
    }

    /**
     * @throws \JsonException
     */
    public function testGetByUsername(): void
    {
        /**
         * Test with empty username
         */
        $response = $this->astrobinWs->getByUsername('', 1);
        $this->assertNull($response);

        /**
         * Test with limit too high
         */
        $user = 'siovene';
        $response = $this->astrobinWs->getByUsername($user, 999_999_999);
        $this->assertNull($response);

        /**
         * Test OK
         */
        $response = $this->astrobinWs->getByUsername($user, 1);
        $this->assertInstanceOf(AstrobinResponse::class, $response);
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($user, $response->username);
    }

    protected function tearDown(): void
    {
        $this->astrobinWs = null;
        $this->badAstrobinWs = null;
    }
}
