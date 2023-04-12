<?php

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Filters\UserFilters;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\User;

/**
 * Class GetUser
 * @package AstrobinWs\Services
 */
class GetUser extends AbstractWebService implements WsInterface
{
    use WsAstrobinTrait;

    final public const END_POINT = 'userprofile';

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
     * @throws WsResponseException
     * @throws \JsonException
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsResponseException(sprintf(WsException::EMPTY_ID, $id), 500, null);
        }

        return $this->sendRequestAndBuildResponse($id, null);
    }

    /**
     * Get user by username
     * @throws \JsonException
     */
    public function getByUsername(string $username, int $limit): ?AstrobinResponse
    {
        if (empty($username) || (parent::LIMIT_MAX < $limit)) {
            return null;
        }

        $params = [
            UserFilters::USERNAME_FILTER->value => $username,
            QueryFilters::LIMIT->value => $limit
        ];
        return $this->sendRequestAndBuildResponse(null, $params);
    }
}
