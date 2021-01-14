<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\UserFilters;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\User;

/**
 * Class GetUser
 * @package AstrobinWs\Services
 */
class GetUser extends AbstractWebService implements WsInterface
{

    private const END_POINT = 'userprofile';

    /**
     * @inheritDoc
     */
    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    /**
     * @inheritDoc
     */
    protected function getObjectEntity(): ?string
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    protected function getCollectionEntity(): ?string
    {
        return null;
    }

    /**
     * Get user by id
     *
     * @param string|null $id
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        $response = $this->get($id, null);
        return $this->buildResponse($response);
    }


    /**
     * Get user by username
     *
     * @param string $username
     * @param int $limit
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getByUsername(string $username, int $limit): ?AstrobinResponse
    {
        if (empty($username)) {
            return null;
        }

        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $response = $this->get(null, [UserFilters::USERNAME_FILTER => $username, [UserFilters::LIMIT => $limit]]);
        return $this->buildResponse($response);
    }
}
