<?php

namespace HamhamFonfon\Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Location;
use Astrobin\WsInterface;

/**
 * Class GetLocation
 * @package HamhamFonfon\Astrobin\Services
 */
class GetLocation extends AbstractWebService implements WsInterface
{

    const END_POINT = 'location/';


    /**
     * @param $location
     * @param $limit
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     */
    public function getLocation($location, $limit)
    {
        $params = ['limit' => $limit];
        return $this->callWs($params);
    }


    /**
     * @param array $params
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new WsResponseException("Response from Astrobin is empty");
        }
        return $this->responseWs($rawResp->objects);
    }

    /**
     * @param array $object
     */
    public function responseWs($object = [])
    {
        $astrobinResponse = [];

        dump($object);
        $astrotest = new Location();
    }


}