<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Filters\UserFilters;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\User;

/**
 * Class GetUser
 * @package AstrobinWs\Services
 */
class GetUser extends AbstractWebService implements WsInterface
{
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
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsResponseException(sprintf(WsException::EMPTY_ID, $id), 500, null);
        }

        try {
            $response = $this->get($id, null);
            $astrobinResponse = $this->buildResponse($response);
        } catch (WsException | \JsonException $e) {
            $astrobinResponse = new AstrobinError($e->getMessage());
        }
        return $astrobinResponse;
    }

    /**
     * Get user by username
     * @throws \JsonException
     */
    public function getByUsername(string $username, int $limit): ?AstrobinResponse
    {
        $response = null;
        if (empty($username)) {
            return null;
        }

        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        try {
            $response = $this->get(null, [
                UserFilters::USERNAME_FILTER->value => $username,
                QueryFilters::LIMIT->value => $limit
            ]);
        } catch (WsException | \JsonException) {
        }
        return $this->buildResponse($response);
    }
}
