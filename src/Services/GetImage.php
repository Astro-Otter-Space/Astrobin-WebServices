<?php

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Collection;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;

/**
 * Class GetImage
 * @package Astrobin\Services
 */
class GetImage extends AbstractWebService implements WsInterface
{

    public const END_POINT = 'image/';

    /**
     * @param $id
     *
     * @return Image|null
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getImageById(?int $id): Image
    {
        if (is_null($id) || !ctype_alnum($id)) {
            throw new WsResponseException(sprintf("[Astrobin response] '%s' is not a correct value, alphanumeric expected", $id), 500, null);
        }
        return $this->callWithId($id);
    }

    /**
     * Return a collection of Image()
     *
     * @param $subjectId
     * @param $limit
     *
     * @return ListImages|Image|null
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getImagesBySubject(string $subjectId, int $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['subjects' => $subjectId, 'limit' => $limit];
        return $this->callWithParams($params);
    }


    /**
     * @param $description
     * @param $limit
     *
     * @return ListImages|Image|null
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getImagesByDescription(string $description, int $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['title__icontains' => urlencode($description), 'limit' => $limit];
        return $this->callWithParams($params);
    }


    /**
     * Return an Collection per user name
     *
     * @param $userName
     * @param $limit
     *
     * @return ListImages|Image|null
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getImagesByUser(string $userName, int $limit)
    {
        if (parent::LIMIT_MAX < $limit) {
            return null;
        }

        $params = ['user' => $userName, 'limit' => $limit];
        return $this->callWithParams($params);
    }


    /**
     * @param string|null $dateFromStr
     * @param string|null $dateToStr
     *
     * @return ListImages|Image|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function getImagesByRangeDate(?string $dateFromStr, ?string $dateToStr)
    {
        if (is_null($dateToStr)) {
            /** @var \DateTimeInterface $dateTo */
            $dateTo = new \DateTime('now');
        } else {
            /** @var \DateTimeInterface $dateTo */
            $dateTo = new \DateTime($dateToStr);
        }

        if (false === strtotime($dateFromStr)) {
            throw new WsException(sprintf("Format \"%s\" is not a correct format, please use YYYY-mm-dd", $dateFromStr), 500, null);
        }

        /** @var \DateTime $dateFrom */
        $dateFrom = \DateTime::createFromFormat('Y-m-d', $dateFromStr);
        if ($dateFromStr !== $dateFrom->format('Y-m-d')) {
            throw new WsException(sprintf("Format \"%s\" is not a correct format for a date, please use YYYY-mm-dd instead", $dateFromStr), 500, null);
        }

        if (false !== $dateFrom && array_sum($dateFrom->getLastErrors())) {
            throw new WsException("Error date format : \n" . print_r($dateFrom->getLastErrors()), 500, null);
        }

        $params = [
            'uploaded__gte' => urlencode($dateFrom->format('Y-m-d 00:00:00')),
            'uploaded__lt' => urlencode($dateTo->format('Y-m-d H:i:s')),
            'limit' => AbstractWebService::LIMIT_MAX
        ];

        return $this->callWithParams($params);
    }

    /**
     * @param $params
     *
     * @return Image|ListImages|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function callWithParams(array $params)
    {
        $rawResp = $this->call(self::END_POINT, AbstractWebService::METHOD_GET, $params, null);

        return $this->callWs($rawResp);
    }

    /**
     * @param $id
     *
     * @return Image|ListImages|null
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function callWithId(int $id)
    {
        $rawResp = $this->call(self::END_POINT, AbstractWebService::METHOD_GET, null, $id);
        return $this->callWs($rawResp);
    }

    /**
     * Call WS "image" with parameters
     *
     * @param $rawResp
     *
     * @return ListImages|Image|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    protected function callWs($rawResp)
    {
        if (!is_object($rawResp)) {
            throw new WsResponseException("Response from Astrobin is empty", 500, null);
        }

        if (property_exists($rawResp, "objects") && property_exists($rawResp, "meta")) {
            if (0 < $rawResp->meta->total_count) {
                return $this->responseWs($rawResp->objects);
            }
            throw new WsResponseException(sprintf("Astrobin doen't find any objects with params : %s", json_encode($params)), 500, null);
        }

        return $this->responseWs([$rawResp]);
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
