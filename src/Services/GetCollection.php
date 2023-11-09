<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\CollectionFilters;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection\ListCollection;
use AstrobinWs\Response\DTO\Item\Collection;

/**
 * Class GetCollection
 * @package Astrobin\Services
 */
class GetCollection extends AbstractWebService implements WsInterface
{
    use WsAstrobinTrait;

    /**
     * @var string
     */
    final public const END_POINT = 'collection';

    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    protected function getObjectEntity(): string
    {
        return Collection::class;
    }

    protected function getCollectionEntity(): string
    {
        return ListCollection::class;
    }


    /**
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsException(sprintf(WsException::EMPTY_ID, $id), 500, null);
        }

        $collection = $this->sendRequestAndBuildResponse($id, null);
        return $this->getImagesFromResource($collection);
    }

    /**
     * @deprecated
     * Request by "user" attribute is not allowed anymore
     * @return ListCollection|null
     * @throws \JsonException
     */
    public function getListCollectionByUser(
        ?string $username,
        ?int $limit
    ): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = [CollectionFilters::USER_FILTER->value => $username, QueryFilters::LIMIT->value => $limit];
        return $this->sendRequestAndBuildResponse(null, $params);
    }
}
