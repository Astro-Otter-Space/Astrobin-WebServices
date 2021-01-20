<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Filters\CollectionFilters;
use AstrobinWs\Filters\ImageFilters;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Collection;
use AstrobinWs\Response\ListCollection;

/**
 * Class GetCollection
 * @package Astrobin\Services
 */
class GetCollection extends AbstractWebService implements WsInterface
{
    use WsAstrobinTrait;

    private const END_POINT = 'collection';

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
     * Only for retro-compatibility with version 1.x
     *
     * @param $id
     *
     * @return AstrobinResponse
     * @throws WsException
     * @throws \ReflectionException|\JsonException
     */
    public function getCollectionById($id): ?AstrobinResponse
    {
        return $this->getById($id);
    }

    /**
     * @param string|null $id
     *
     * @return AstrobinResponse
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
     * @param string|null $username
     * @param int|null $limit
     *
     * @return ListCollection|null
     * @throws WsException
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function getListCollectionByUser(?string $username, ?int $limit): ?AstrobinResponse
    {
        if (parent::LIMIT_MAX < $limit) {
            $limit = parent::LIMIT_MAX;
        }
        $params = [CollectionFilters::USER_FILTER => $username, ImageFilters::LIMIT => $limit];
        /** @var ListCollection $astrobinListCollection */
        $response = $this->get(null, $params);

        return $this->buildResponse($response);
    }
}
