<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:16
 */

namespace HamhamFonfon\Astrobin\Services;

use HamhamFonfon\Astrobin\AbstractWebService;
use HamhamFonfon\Astrobin\Exceptions\AstrobinResponseExceptions;
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
     * @throws \HamhamFonfon\Astrobin\Exceptions\WsResponseException
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
            dump($urlTest);
            dump(file_get_contents($urlTest));
//            if (preg_match('/\/([\d]+)/', $astrobinToday->image, $matches)) {
//                $imageId = $matches[1];
//                dump(AstrobinWebService::ASTROBIN_URL . $astrobinToday->image);
//                $sndRawCall = $this->call(GetImage::END_POINT, parent::METHOD_GET, $imageId);
//                dump($sndRawCall);
//            }
        }

        die();
    }


    /**
     * @param array $params
     * @return Image
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     */
    public function callWs($params = [])
    {
        /** @var  $rawResp */
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, $params);
        if (!isset($rawResp->objects) || 0 == $rawResp->meta->total_count) {
            throw new AstrobinResponseExceptions("Response from Astrobin is empty");
        }
        return $rawResp->objects;
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