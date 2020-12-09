<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Collection;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListCollection;

/**
 * Class GetCollection
 * @package Astrobin\Services
 */
class GetCollection extends AbstractWebService implements WsInterface
{

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
     * @throws \ReflectionException
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

        $astrobinCollection = $this->get($id, null);
        if (isset($astrobinCollection->images) && 0 < count($astrobinCollection->images)) {
            $astrobinCollection = $this->getImagesCollection($astrobinCollection);
        }

        return $astrobinCollection;
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
        $params = ['user' => $username, 'limit' => $limit];
        /** @var ListCollection $astrobinListCollection */
        $response = $this->get(null, $params);

        return $this->buildResponse($response);
    }


    /**
     * Retrieve images of a collection by WS GetImage
     * @param Collection $astrobinCollection
     * @return AstrobinResponse|Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    private function getImagesCollection(Collection $astrobinCollection): ?AstrobinResponse
    {
        $listImagesId = array_map(static function ($path) {
            if (preg_match('/\/([\d]+)/', $path, $matches)) {
                return $matches[1];
            }
        }, $astrobinCollection->images);
        if (0 < count($listImagesId)) {
            foreach ($listImagesId as $imageId) {
                $getImage = new GetImage();
                $imgRawCall = $getImage->getById($imageId);

                $image = new Image();
                $image->fromObj($imgRawCall);

                $astrobinCollection->add($image);
            }
        }

        return $astrobinCollection;
    }

    /**
     * @param string $response
     *
     * @return AstrobinResponse|ListCollection|Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException|\JsonException
     */
    public function buildResponse(string $response): ?AstrobinResponse
    {
        $object = $this->deserialize($response);
        $astrobinResponse = null;

        /** @var Collection $entity */
        $entity = $this->getObjectEntity();
        /** @var ListCollection $collectionEntity */
        $collectionEntity = $this->getCollectionEntity();

        if (is_array($object) && 0 < count($object)) {
            if (1 < count($response)) {
                $astrobinResponse = new $collectionEntity();
                foreach ($object as $strCollection) {
                    $collection = new Collection();
                    $collection->fromObj($strCollection);

                    //$this->getImagesCollection($collection);
                    //$astrobinResponse->add($collection);
                }
            } else {
                $astrobinResponse = new $entity();
                $astrobinResponse->fromObj($object);
            }
        }

        return $astrobinResponse;
    }
}
