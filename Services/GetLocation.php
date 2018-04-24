<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 18:26
 */

namespace HamhamFonfon\Astrobin\Services;

use HamhamFonfon\Astrobin\AstrobinInterface;
use HamhamFonfon\Astrobin\AstrobinWebService;
use HamhamFonfon\Astrobin\Exceptions\AstrobinResponseExceptions;
use HamhamFonfon\Astrobin\Response\AstrobinLocation;


/**
 * Class GetLocation
 * @package AppBundle\Astrobin\Services
 */
class GetLocation extends AstrobinWebService implements AstrobinInterface
{

    const END_POINT = 'location/';


    public function getLocation($location, $limit)
    {

        $params = ['limit' => $limit];

        return $this->callWs($params);
    }

    /**
     * @param array $params
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     */
    public function callWs($params = [])
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new AstrobinResponseExceptions("Response from Astrobin is empty");
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
        $astrotest = new AstrobinLocation();
    }


}