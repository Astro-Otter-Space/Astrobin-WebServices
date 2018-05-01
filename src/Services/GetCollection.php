<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsException;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Collection;
use Astrobin\Response\Image;
use Astrobin\Response\ListCollection;

/**
 * Class GetCollection
 * @package Astrobin\Services
 */
class GetCollection extends AbstractWebService
{

    const END_POINT = 'collection/';


    /**
     * @param null $id
     * @return Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function getCollectionById($id = null)
    {
        if (is_null($id) || empty($id)) {
            throw new WsException('Astrobon Webservice Collection : id empty');
        }

        $astrobinCollection = $this->callWs($id);
        if (isset($astrobinCollection->images) && 0 < count($astrobinCollection->images)) {
            $astrobinCollection = $this->getImagesCollection($astrobinCollection);
        }

        return $astrobinCollection;
    }


    /**
     * @param null $username
     * @return ListCollection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function getListCollectionByUser($username = null)
    {
        $params = ['user' => $username];
        /** @var ListCollection $astrobinListCollection */
        $astrobinListCollection = $this->callWs($params);
        foreach ($astrobinListCollection->getIterator() as $collection) {
            /** @var Collection $ollection */
            $ollection = $this->getImagesCollection($collection);
            $astrobinListCollection->add($collection);
        }

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
    private function getImagesCollection(Collection $astrobinCollection)
    {
        $listImagesId = preg_grep('/\/([\d]+)/', $astrobinCollection->images);
        if (0 < count($listImagesId)) {
            foreach ($listImagesId as $imageId) {
                $imgRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);

                $image = new Image();
                $image->fromObj($imgRawCall);

                $astrobinCollection->add($image);
            }
        }

        return $astrobinCollection;
    }

    /**
     * @param array|integer $params
     * @return ListCollection|Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!is_object($rawResp)) {
            throw new WsResponseException("Response from Astrobin is empty");
        } else {
            if (property_exists($rawResp, "objects") && property_exists($rawResp, "meta") && 0 < $rawResp->meta->total_count) {
                return $this->responseWs($rawResp->objects);
            } else {
                return $this->responseWs([$rawResp]);
            }
        }
    }


    /**
     * @param $objects
     * @return Collection|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($objects)
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {
            if (1 < count($objects)) {
                /** @var ListCollection $astrobinResponse */
                $astrobinResponse = new ListCollection();
                foreach ($objects as $object) {
                    $collection = new Collection();
                    $collection->fromObj($object);
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