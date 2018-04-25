<?php

namespace HamhamFonfon\Astrobin\Services;

use HamhamFonfon\Astrobin\AbstractWebService;
use HamhamFonfon\Astrobin\AstrobinInterface;
use HamhamFonfon\Astrobin\Exceptions\AstrobinResponseExceptions;
use HamhamFonfon\Astrobin\Response\AstrobinCollection;
use HamhamFonfon\Astrobin\Response\AstrobinImage;

/**
 * Class getObject
 * @package HamhamFonfon\Astrobin\Services
 */
class GetImage extends AbstractWebService implements AstrobinInterface
{

    const END_POINT = 'image/';


    /**
     * @param $id
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \HamhamFonfon\Astrobin\Exceptions\AstrobinException
     * @throws \ReflectionException
     */
    public function getImageById($id)
    {
        return $this->callWs($id);
    }



    /**
     * Return a collection of AstrobinImage()
     * @param $subjectId
     * @param $limit
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \HamhamFonfon\Astrobin\Exceptions\AstrobinException
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
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \HamhamFonfon\Astrobin\Exceptions\AstrobinException
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
     * Return an AstrobinCollection per user name
     * @param $userName
     * @param $limit
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \HamhamFonfon\Astrobin\Exceptions\AstrobinException
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
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \HamhamFonfon\Astrobin\Exceptions\AstrobinException
     * @throws \ReflectionException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call(self::END_POINT, AstrobinWebService::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new AstrobinResponseExceptions("Response from Astrobin is empty");
        }
        return $this->responseWs($rawResp->objects);
    }


    /**
     * Build response from WebService Astrobin
     *
     * @param array $objects
     * @return AstrobinCollection|AstrobinImage|null
     * @throws \ReflectionException
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {

            if (1 < count($objects)) {
                /** @var AstrobinCollection $astrobinCollection */
                $astrobinResponse = new AstrobinCollection();
                $astrobinResponse->setImages($objects);

            } else {
                /** @var AstrobinImage $astrobinResponse */
                $astrobinResponse = new AstrobinImage();
                $astrobinResponse->fromObj($objects[0]);
            }
        }

        return $astrobinResponse;
    }
}