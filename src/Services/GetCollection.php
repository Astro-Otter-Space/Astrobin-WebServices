<?php

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
     * @param int $id
     *
     * @return AstrobinResponse
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getById(int $id): ?AstrobinResponse
    {
        if (is_null($id)) {
            throw new WsException('Astrobin Webservice Collection : id empty', 500, null);
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
     * @param string $object
     *
     * @return AstrobinResponse|ListCollection|Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function buildResponse(string $object): ?AstrobinResponse
    {
        $astrobinResponse = null;
        if (is_array($object) && 0 < count($object)) {
            if (1 < count($object)) {
                /** @var ListCollection $astrobinResponse */
                $astrobinResponse = new ListCollection();
                foreach ($object as $object) {
                    $collection = new Collection();
                    $collection->fromObj($object);

                    $this->getImagesCollection($collection);
                    $astrobinResponse->add($collection);
                }
            } else {
                /** @var Collection $astrobinResponse */
                $astrobinResponse = new Collection();
                $astrobinResponse->fromObj($object[0]);
            }
        }

        return $astrobinResponse;
    }
}
