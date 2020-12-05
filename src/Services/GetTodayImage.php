<?php

namespace AstrobinWs\Services;

use Astrobin\Response\AstrobinResponse;
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

    private const END_POINT = 'imageoftheday';

    public const FORMAT_DATE_ASTROBIN = "Y-m-d";

    /**
     * @return string
     */
    protected function getEndPoint(): string
    {
        return self::END_POINT;
    }

    /**
     * @param int $id
     *
     * @return \http\Client\Response
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getById(int $id)
    {
        $response = $this->get($id, null);
        return $this->buildResponse($response);
    }


    /**
     * @param $offset
     * @param $limit
     * @return Today
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function getDayImage(?int $offset, ?int $limit)
    {
        if (is_null($limit)) {
            $limit = 1;
        }
        if (is_null($offset)) {
            $offset = parent::LIMIT_MAX;
        }

        $params = [
            'limit' => $limit,
            'offset' => $offset
        ];

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
     * @param $objects
     *
     * @return AstrobinResponse|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function buildResponse(array $objects):? AstrobinResponse
    {
        $astrobinResponse = null;
        if (is_array($objects) && 0 < count($objects)) {
            $astrobinResponse = new Today();
            $astrobinResponse->fromObj($objects[0]);
        }

        return $astrobinResponse;
    }

}
