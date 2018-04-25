<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace HamhamFonfon\Astrobin\Services;

use HamhamFonfon\Astrobin\AbstractWebService;
use HamhamFonfon\Astrobin\Exceptions\WsResponseException;
use HamhamFonfon\Astrobin\Response\Image;
use HamhamFonfon\Astrobin\Response\Today;
use HamhamFonfon\Astrobin\WsInterface;

/**
 * Class getTodayImage
 * @package AppBundle\Astrobin\Services
 */
class getTodayImage extends AbstractWebService implements WsInterface
{

    const END_POINT = 'imageoftheday/';

    const FORMAT_DATE_ASTROBIN = "Y-m-d";


    /**
     * @throws WsResponseException
     * @throws \HamhamFonfon\Astrobin\Exceptions\WsException
     * @throws \ReflectionException
     */
    public function getTodayImage()
    {
        $rawResp = $this->callWs(['limit' => 1]);

        $astrobinToday = new Today();
        $astrobinToday->fromObj($rawResp[0]);
        $today = new \DateTime('now');
        if ($today->format(self::FORMAT_DATE_ASTROBIN) == $astrobinToday->date) {

            $urlTest = AbstractWebService::ASTROBIN_URL . substr($astrobinToday->image, strrpos($astrobinToday->image, '/v1/')+strlen('/v1/'));
//            dump($urlTest);
//            dump(file_get_contents($urlTest));
//            if (preg_match('/\/([\d]+)/', $astrobinToday->image, $matches)) {
//                $imageId = $matches[1];
//                dump(AstrobinWebService::ASTROBIN_URL . $astrobinToday->image);
//                $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);
//                dump($sndRawCall);
//            }
        }
    }


    /**
     * @param array $params
     * @return mixed
     * @throws WsResponseException
     * @throws \HamhamFonfon\Astrobin\Exceptions\WsException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new WsResponseException("Response from Astrobin is empty");
        }
        return $this->responseWs($rawResp->objects);
    }


    /**
     * @param array $objects
     * @return array
     */
    public function responseWs($objects = [])
    {
        $astrobinResponse = [];
        return $astrobinResponse;
    }
}