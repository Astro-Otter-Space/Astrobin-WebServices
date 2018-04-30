<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Image;
use Astrobin\Response\Today;
use Astrobin\WsInterface;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class GetTodayImage extends AbstractWebService implements WsInterface
{

    const END_POINT = 'imageoftheday/';

    const FORMAT_DATE_ASTROBIN = "Y-m-d";


    /**
     * @param $offset
     * @param $limit
     * @return Today
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getDayImage($offset = null, $limit = 1)
    {
        $params = ['limit' => $limit];
        if (isset($offset) && is_numeric($offset)) {
            $params['offset'] = $offset;
        }

        $astrobinToday = $this->callWs($params);

        // For Image of the day
        if (is_null($offset)) {
            $today = new \DateTime('now');
            // If it is not today, take yesterday image
            $params['offset'] = (($today->format(self::FORMAT_DATE_ASTROBIN) === $astrobinToday->date)) ?: 1;
        }

        if (preg_match('/\/([\d]+)/', $astrobinToday->resource_uri, $matches)) {
            $imageId = $matches[1];
            $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);

            $image = new Image();
            $image->fromObj($sndRawCall);

            $astrobinToday->add($image);
        }

        return $astrobinToday;
    }


    /**
     * @return Today
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getTodayDayImage()
    {
        return $this->getDayImage();
    }

    /**
     * @param array $params
     * @return Today
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
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
     * @param array $objects
     * @return Today|null $astrobinResponse
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {
            $astrobinResponse = new Today();
            $astrobinResponse->fromObj($objects[0]);
        }

        return $astrobinResponse;
    }
}