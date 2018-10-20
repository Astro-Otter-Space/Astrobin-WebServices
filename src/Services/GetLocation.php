<?php

namespace Astrobin\Services;

use Astrobin\AbstractWebService;
use Astrobin\Exceptions\WsResponseException;
use Astrobin\Response\Location;

/**
 * Class GetLocation
 * @package Astrobin\Services
 */
class GetLocation extends AbstractWebService implements WsInterface
{

    const END_POINT = 'location/';


    /**
     * @param $id
     * @return Location|null
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getLocationById($id): Location
    {
        return $this->callWs($id);
    }


    /**
     * @param array $params
     * @return Location|null
     * @throws WsResponseException
     * @throws \Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function callWs($params = []): Location
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new WsResponseException("Response from Astrobin is empty");
        }
        return $this->responseWs($rawResp->objects);
    }

    /**
     * @param \stdClass $object
     * @return Location|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs($object = null): Location
    {
        $astrobinResponse = null;

        $astrobinResponse = new Location();
        $astrobinResponse->fromObj($object);

        return $astrobinResponse;
    }
}
