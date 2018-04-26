<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsResponseException;
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
     * @return Today
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getTodayImage()
    {
        $astrobinToday = $this->callWs(['limit' => 1]);
        $today = new \DateTime('now');
        if ($today->format(self::FORMAT_DATE_ASTROBIN) == $astrobinToday->date) {

            if (preg_match('/\/([\d]+)/', $astrobinToday->image, $matches)) {
                $imageId = $matches[1];
                $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);
                dump($sndRawCall);
                $astrobinToday->addImage($sndRawCall);
            }
        }

        return $astrobinToday;
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
        return $this->responseWs($rawResp->objects);
    }


    /**
     * @param array $objects
     * @return Today
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