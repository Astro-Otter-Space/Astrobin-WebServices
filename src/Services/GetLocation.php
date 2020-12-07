<?php

declare(strict_types=1);

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
     * @param int|null $id
     *
     * @return AstrobinResponse|null
     * @throws WsException
     * @throws WsResponseException
     */
    public function getById(?int $id): ?AstrobinResponse
    {
        if (is_null($id) || !ctype_alnum($id)) {
            throw new WsResponseException(sprintf("[Astrobin response] '%s' is not a correct value, alphanumeric expected", $id), 500, null);
        }
        $response = $this->get($id, null);
        return $this->buildResponse($response);
    }

    /**
     * @deprecated
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

    /**
     * @param string $object
     *
     * @return AstrobinResponse|null
     */
    public function buildResponse(string $object): ?AstrobinResponse
    {
        return null;
    }
}
