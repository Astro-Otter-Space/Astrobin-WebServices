<?php

namespace AstrobinWs\Services;

use AstrobinWs\AbstractWebService;
use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Location;

/**
 * Class GetLocation
 * @package Astrobin\Services
 */
class GetLocation extends AbstractWebService implements WsInterface
{

    private const END_POINT = 'location';

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
     * @return AstrobinResponse|null
     * @throws WsException
     */
    public function getById($id):? AstrobinResponse
    {
        if (is_null($id) || !ctype_alnum($id)) {
            throw new WsResponseException(sprintf("[Astrobin response] '%s' is not a correct value, alphanumeric expected", $id), 500, null);
        }
        $response = $this->get($id, null);
        return $this->buildResponse($response);
    }

    /**
     * @param int $id
     *
     * @return Location|null
     * @throws WsResponseException
     * @throws WsException
     * @throws \ReflectionException
     */
    public function callWithId(int $id): Location
    {
        $rawResp = $this->call(self::END_POINT, parent::METHOD_GET, null, $id);
        return $this->callWs($rawResp);
    }

    /**
     * @param array $params
     */
    public function callWithParams(array $params): void
    {
    }


    /**
     * @param $rawResp
     *
     * @return Location|null
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    protected function callWs($rawResp): Location
    {
        if (!isset($rawResp->objects) || 0 === $rawResp->meta->total_count) {
            throw new WsResponseException("Response from Astrobin is empty", 500, null);
        }
        return $this->responseWs($rawResp->objects);
    }

    /**
     * @param array $object
     *
     * @return Location
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function responseWs(array $object): Location
    {
        $astrobinResponse = new Location();
        $astrobinResponse->fromObj($object);

        return $astrobinResponse;
    }
}
