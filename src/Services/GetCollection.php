<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsException;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Collection;
use Astrobin\Response\Image;

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
            $listImagesId = preg_grep('/\/([\d]+)/', $astrobinCollection->images);
            if (0 < count($listImagesId)) {
                foreach ($listImagesId as $imageId) {
                    $imgRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);

                    $image = new Image();
                    $image->fromObj($imgRawCall);

                    $astrobinCollection->add($image);
                }
            }
        }

        return $astrobinCollection;
    }


    /**
     * @param $idCollection
     * @return Collection|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function callWs($idCollection)
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $idCollection);
        if (!is_object($rawResp)) {
            throw new WsResponseException("Response from Astrobin is empty");
        } else {
            return $this->responseWs($rawResp);
        }
    }

    /**
     * @param $object
     * @return Collection|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($object)
    {
        $astrobinresponse = null;
        if (is_array($object) && 0 < count($object)) {
            $astrobinresponse = new Collection();
            $astrobinresponse->fromObj($object);
        }
        return $astrobinresponse;
    }
}