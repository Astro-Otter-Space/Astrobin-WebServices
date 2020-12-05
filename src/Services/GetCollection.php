<?php

namespace AstrobinWs\Services;

use Astrobin\Response\AstrobinResponse;
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
     * @param int|null $id
     * @return Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function getById(?int $id): Collection
    {
        if (is_null($id)) {
            throw new WsException('Astrobon Webservice Collection : id empty', 500, null);
        }

        $astrobinCollection = $this->get($id);
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
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function getListCollectionByUser(?string $username, ?int $limit): ListCollection
    {
        if (parent::LIMIT_MAX < $limit) {
            $limit = parent::LIMIT_MAX;
        }
        $params = ['user' => $username, 'limit' => $limit];
        /** @var ListCollection $astrobinListCollection */
        $response = $this->get(null, $params);

        return $astrobinListCollection;
    }


    /**
     * Retrieve images of a collection by WS GetImage
     * @param Collection $astrobinCollection
     * @return Collection
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    private function getImagesCollection(Collection $astrobinCollection): Collection
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
     * @param array $response
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function buildResponse($response):? AstrobinResponse
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {
            if (1 < count($objects)) {
                /** @var ListCollection $astrobinResponse */
                $astrobinResponse = new ListCollection();
                foreach ($objects as $object) {
                    $collection = new Collection();
                    $collection->fromObj($object);

                    $this->getImagesCollection($collection);
                    $astrobinResponse->add($collection);
                }
            } else {
                /** @var Collection $astrobinResponse */
                $astrobinResponse = new Collection();
                $astrobinResponse->fromObj($objects[0]);
            }
        }
        return $astrobinResponse;
    }
}
