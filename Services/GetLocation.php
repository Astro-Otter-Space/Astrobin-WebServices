<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 18:26
 */

namespace HamhamFonfon\Astrobin\Services;

use HamhamFonfon\Astrobin\AbstractWebService;
use HamhamFonfon\Astrobin\Exceptions\WsResponseException;
use HamhamFonfon\Astrobin\Exceptions\AstrobinResponseExceptions;
use HamhamFonfon\Astrobin\Response\Location;
use HamhamFonfon\Astrobin\WsInterface;


/**
 * Class GetLocation
 * @package AppBundle\Astrobin\Services
 */
class GetLocation extends AbstractWebService implements WsInterface
{

    const END_POINT = 'location/';


    public function getLocation($location, $limit)
    {

        $params = ['limit' => $limit];

        return $this->callWs($params);
    }


    /**
     * @param array $params
     * @throws WsResponseException
     * @throws \HamhamFonfon\Astrobin\Exceptions\WsException
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
     * @param $object
     */
    public function responseWs($object = [])
    {
        $astrobinResponse = [];

        dump($object);
        $astrotest = new Location();
    }


}