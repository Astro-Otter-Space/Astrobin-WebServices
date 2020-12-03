<?php

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListImages;
use AstrobinWs\Response\Today;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class GetTodayImage extends AbstractWebService implements WsInterface
{

    public const END_POINT = 'imageoftheday/';

    public const FORMAT_DATE_ASTROBIN = "Y-m-d";

    /**
     * @param $offset
     * @param $limit
     * @return Today
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getDayImage(?int $offset, ?int $limit): Today
    {
        if (is_null($limit)) {
            $limit = 1;
        }
        $params = ['limit' => $limit];
        if (isset($offset) && is_numeric($offset)) {
            $params['offset'] = $offset;
        }

        $astrobinToday = $this->callWithParams($params);

        // For Image of the day
        if (is_null($offset)) {
            /** @var \DateTimeInterface $today */
            $today = new \DateTime('now');
            // If it is not today, take yesterday image
            $params['offset'] = (($today->format(self::FORMAT_DATE_ASTROBIN) === $astrobinToday->date)) ?: 1;
        }

        if (preg_match('/\/([\d]+)/', $astrobinToday->resource_uri, $matches)) {
            $imageId = $matches[1];
            $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, null, $imageId);

            $image = new Image();
            $image->fromObj($sndRawCall);

            $astrobinToday->add($image);
        }

        return $astrobinToday;
    }


    /**
     * @return Today
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getTodayDayImage(): Today
    {
        return $this->getDayImage(0, 1);
    }

    /**
     * @param array $params
     *
     * @return Today
     * @throws WsException
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function callWithParams(array $params): Today
    {
        $rawResp = $this->call(self::END_POINT, AbstractWebService::METHOD_GET, $params, null);

        return $this->callWs($rawResp);
    }

    /**
     * @param $rawResp
     *
     * @return Today
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    protected function callWs($rawResp): Today
    {
        if (property_exists($rawResp, "objects") && property_exists($rawResp, "meta") && 0 < $rawResp->meta->total_count) {
            return $this->responseWs($rawResp->objects);
        }

        return $this->responseWs([$rawResp]);
    }

    /**
     * @param $objects
     *
     * @return Today|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($objects):? Today
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {
            $astrobinResponse = new Today();
            $astrobinResponse->fromObj($objects[0]);
        }

        return $astrobinResponse;
    }


    /**
     * @param int $id
     */
    public function callWithId(int $id): void
    {
    }
}
