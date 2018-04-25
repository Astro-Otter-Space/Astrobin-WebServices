<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Collection;
use Astrobin\Response\Image;
use Astrobin\WsInterface;

/**
 * Class GetImage
 * @package Astrobin\Services
 */
class GetImage extends AbstractWebService implements WsInterface
{

    const END_POINT = 'image/';


    /**
     * @param $id
     * @return Collection|Image|null
     * @throws \ReflectionException
     */
    public function getImageById($id)
    {
        return $this->callWs($id);
    }



    /**
     * Return a collection of Image()
     *
     * @param $subjectId
     * @param $limit
     * @return Collection|Image|null
     * @throws \ReflectionException
     */
    public function getImagesBySubject($subjectId, $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['subjects' => $subjectId, 'limit' => $limit];
        return $this->callWs($params);
    }


    /**
     * @param $description
     * @param $limit
     * @return Collection|Image|null
     * @throws \ReflectionException
     */
    public function getImagesByDescription($description, $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['title__icontains' => urlencode($description), 'limit' => $limit];
        return $this->callWs($params);
    }


    /**
     * Return an Collection per user name
     * @param $userName
     * @param $limit
     * @return Collection|Image|null
     * @throws \ReflectionException
     */
    public function getImagesByUser($userName, $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['user' => $userName, 'limit' => $limit];
        return $this->callWs($params);
    }


    /**
     * Call WS "image" with parameters
     * @param array $params
     * @return Collection|Image|null
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call(self::END_POINT, AbstractWebService::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new WsResponseException("Response from Astrobin is empty");
        }
        return $this->responseWs($rawResp->objects);
    }


    /**
     * Build response from WebService Astrobin
     * @param array $objects
     * @return Collection|Image|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {

            if (1 < count($objects)) {
                /** @var Collection $astrobinCollection */
                $astrobinResponse = new Collection();
                $astrobinResponse->setImages($objects);

            } else {
                /** @var Image $astrobinResponse */
                $astrobinResponse = new Image();
                $astrobinResponse->fromObj($objects[0]);
            }
        }

        return $astrobinResponse;
    }
}