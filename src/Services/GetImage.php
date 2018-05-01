<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsException;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Collection;
use Astrobin\Response\Image;
use Astrobin\Response\ListImages;
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
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
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
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
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
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
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
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
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
     * @param null $dateFromStr
     * @param null $dateToStr
     * @return Collection|Image|null
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getImagesByRangeDate($dateFromStr = null, $dateToStr = null)
    {

        if (is_null($dateToStr)) {
            $dateTo = new \DateTime('now');
        } else {
            $dateTo = new \DateTime($dateToStr);
        }

        if (false === strtotime($dateFromStr)) {
            throw new WsException(sprintf("Format \"%s\" is not a correct format, please use YYYY-mm-dd", $dateFromStr));
        }

        $dateFrom = \DateTime::createFromFormat('Y-m-d', $dateFromStr);
        if (false === $dateFrom) {
            throw new WsException(sprintf("Format \"%s\" is not a correct format, please use YYYY-mm-dd", $dateFromStr));

        } elseif (false !== $dateFrom && array_sum($dateFrom->getLastErrors())) {
            throw new WsException("Error date format : \n" . print_r($dateFrom->getLastErrors()));
        }

        $params = [
            'uploaded__gte' => urlencode($dateFrom->format('Y-m-d 00:00:00')),
            'uploaded__lt' => urlencode($dateTo->format('Y-m-d H:i:s')),
            'limit' => AbstractWebService::LIMIT_MAX
        ];

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
        if (!is_object($rawResp)) {
            throw new WsResponseException("Response from Astrobin is empty");
        } else {
            if (property_exists($rawResp, "objects") && property_exists($rawResp, "meta")) {
                if (0 < $rawResp->meta->total_count) {
                    return $this->responseWs($rawResp->objects);
                }
                throw new WsResponseException(sprintf("Astrobin doen't find any objects with params : %s", json_encode($params)));

            } else {
                return $this->responseWs([$rawResp]);
            }
        }
        return null;
    }


    /**
     * Build response from WebService Astrobin
     * @param array $objects
     * @return ListImages|Image|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {

            if (1 < count($objects)) {
                /** @var Collection $astrobinCollection */
                $astrobinResponse = new ListImages();
                foreach ($objects as $object) {
                    $image = new Image();
                    $image->fromObj($object);
                    $astrobinResponse->add($image);
                }

            } else {
                /** @var Image $astrobinResponse */
                $astrobinResponse = new Image();
                $astrobinResponse->fromObj($objects[0]);
            }
        } else {
            $astrobinResponse = new Image();
            $astrobinResponse->fromObj($objects[0]);
        }

        return $astrobinResponse;
    }
}