<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Filters\CollectionFilters;
use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection;
use AstrobinWs\Response\DTO\ListCollection;

/**
 * Class GetCollection
 * @package Astrobin\Services
 */
class GetCollection extends AbstractWebService implements WsInterface
{
    use WsAstrobinTrait;

    final public const END_POINT = 'collection';

    /**
     * @return string
     */
    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    /**
     * @return string
     */
    protected function getObjectEntity(): string
    {
        return Collection::class;
    }

    /**
     * @return string
     */
    protected function getCollectionEntity(): string
    {
        return ListCollection::class;
    }


    /**
     * @param string|null $id
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function getById(?string $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsResponseException(sprintf(WsException::EMPTY_ID, $id), 500, null);
        }

        $response = $this->get($id, null);
        $collection = $this->buildResponse($response);

        if (!is_null($collection)) {
            return $this->getImagesFromResource($collection);
        }

        return new AstrobinError(WsException::ERR_EMPTY);
    }


    /**
     * @return ListCollection|null
     * @throws WsException
     * @throws \JsonException
     */
    public function getListCollectionByUser(?string $username, ?int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            $limit = parent::LIMIT_MAX;
        }
        $params = [CollectionFilters::USER_FILTER->value => $username, QueryFilters::LIMIT->value => $limit];
        /** @var ListCollection $astrobinListCollection */
        $response = $this->get(null, $params);

        return $this->buildResponse($response);
    }
}
