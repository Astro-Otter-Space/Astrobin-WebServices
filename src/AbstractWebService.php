<?php

declare(strict_types=1);

namespace AstrobinWs;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Collection;
use AstrobinWs\Response\Image;
use AstrobinWs\Response\ListCollection;
use AstrobinWs\Response\ListImages;
use AstrobinWs\Response\Today;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use http\Client\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class AstrobinWebService
 *
 * @package AppBundle\Astrobin
 */
abstract class AbstractWebService
{
    public const LIMIT_MAX = 20;
    public const TIMEOUT = 30;

    protected $timeout;
    private $apiKey;
    private $apiSecret;

    protected static $headers = [
        'Accept' => GuzzleSingleton::APPLICATION_JSON,
        'Content-Type' => GuzzleSingleton::APPLICATION_JSON
    ];

    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractWebService constructor.
     */
    public function __construct()
    {
        $this->apiKey = getenv('ASTROBIN_API_KEY');
        $this->apiSecret = getenv('ASTROBIN_API_SECRET');
        $this->timeout = self::TIMEOUT;
        $this->buildFactory();
    }


    /**
     * Get Guzzle Instance
     */
    public function buildFactory(): void
    {
        $this->client = GuzzleSingleton::getInstance();
    }

    /**
     * Return endpoint
     */
    abstract protected function getEndPoint(): string;

    /**
     * Get Instance of response object Entity
     *
     * @return string
     */
    abstract protected function getObjectEntity(): ?string;

    /**
     * Get instance of response collection entity
     * @return string
     */
    abstract protected function getCollectionEntity(): ?string;

    /**
     * Build EndPoint
     *
     * @param string|null $param
     *
     * @return string
     */
    private function buildEndpoint(?string $param): string
    {
        return (!is_null($param)) ? sprintf('/api/v1/%s/%s', $this->getEndPoint(), $param) : sprintf('/api/v1/%s', $this->getEndPoint());
    }

    /**
     * @param string|null $id
     * @param array|null $queryParams
     *
     * @return \stdClass|null
     * @throws WsException
     * @throws \JsonException
     */
    protected function get(?string $id, ?array $queryParams): ?string
    {
        return $this->buildRequest($id, null, $queryParams, null, GuzzleSingleton::METHOD_GET);
    }

    /**
     * NOT USED, just for example
     *
     * @param string $id
     * @param array|null $queryParams
     * @param array|null $body
     *
     * @return \stdClass|null
     * @throws WsException
     * @throws \JsonException
     */
    protected function post(string $id, ?array $queryParams, ?array $body): ?string
    {
        $this->buildRequest($id, $body, $queryParams, null, GuzzleSingleton::METHOD_POST);
    }

    /**
     * @param string|null $id
     * @param array|null $body
     * @param array|null $queryParams
     * @param array|null $headers
     * @param string $method
     *
     * @return \stdClass|null
     * @throws WsException
     * @throws \JsonException
     */
    private function buildRequest(?string $id, ?array $body, ?array $queryParams, ?array $headers, string $method): ?string
    {
        if (is_null($this->apiKey) || is_null($this->apiSecret)) {
            throw new WsException(WsException::KEYS_ERROR, 500, null);
        }

        $endPoint = $this->buildEndpoint($id);
        if (!is_null($headers)) {
            $options['headers'] = array_merge(self::$headers, $headers);
        } else {
            $options['headers'] = self::$headers;
        }

        $astrobinParams = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        if (is_null($queryParams)) {
            $queryParams = [];
        }

        $options = [
            'query' => array_filter(array_merge($astrobinParams, $queryParams))
        ];

        if (!is_null($body) && !empty($body)) {
            $options['body'] = $body;
        }

        $responseGuzzle = null;
        try {
            /** @var ResponseInterface $responseGuzzle */
            $responseGuzzle = $this->client->request($method, $endPoint, $options);
        } catch (GuzzleException $e) {
            $msgErr = $e->getMessage();
        }

        if ($responseGuzzle instanceof ResponseInterface) {
            return $this->getResponse($responseGuzzle);
        }

        throw new WsException($msgErr, 500, null);
    }


    /**
     * Check response and jsondecode object
     *
     * @param ResponseInterface $response
     *
     * @return mixed|null
     * @throws WsException|\JsonException
     */
    public function getResponse(ResponseInterface $response): string
    {
        if (200 !== $response->getStatusCode()) {
            throw new WsException(sprintf(WsException::GUZZLE_RESPONSE, $response->getReasonPhrase()), 500, null);
        }

        /**
         * @var StreamInterface $body
        */
        $body = $response->getBody();

        if (false === $body->isReadable()) {
            throw new WsException(WsException::ERR_READABLE, 500, null);
        }

        if (is_null($body->getSize()) || 0 === $body->getSize()) {
            throw new WsException(sprintf(WsException::ERR_EMPTY, $this->getEndPoint()), 500, null);
        }

        $contents = $body->getContents();
        if (false === strpos($contents, '{', 0)) {
            throw new WsException(sprintf(WsException::ERR_JSON, (string)$body), 500, null);
        }

        $jsonContent = json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        if (property_exists($jsonContent, 'meta') && 0 === $jsonContent->meta->total_count) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        return $contents;
    }

    /**
     * Convert string into Json
     *
     * @param string $contents
     *
     * @return \stdClass
     * @throws \JsonException
     * @throws WsResponseException
     */
    protected function deserialize(string $contents): \stdClass
    {
        $responseJson = json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        if (property_exists($responseJson, "objects") && property_exists($responseJson, "meta") && 0 === $responseJson->meta->total_count) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        return $responseJson;
    }

    /**
     * Build response from WebService Astrobin
     *
     * @param string $response
     *
     * @return AstrobinResponse
     * @throws WsResponseException
     * @throws \ReflectionException|\JsonException
     */
    protected function buildResponse(string $response): ?AstrobinResponse
    {
        $astrobinResponse = null;
        if (is_null($response)) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        $object = $this->deserialize($response);

        /** @var Image|Today|Collection|AstrobinResponse $entity */
        $entity = $this->getObjectEntity();

        /** @var ListImages|ListCollection|ListImages|AstrobinResponse $collectionEntity */
        $collectionEntity = $this->getCollectionEntity();

        if (property_exists($object, "objects") && 0 < count($object->objects)) {
            $listObjects = $object->objects;
            if (1 < count($listObjects)) {
                $astrobinResponse = new $collectionEntity();
                foreach ($listObjects as $object) {
                    $entity = new $entity();
                    $entity->fromObj($object);
                    $astrobinResponse->add($entity);
                }
            } else {
                $astrobinResponse = new $entity();
                $astrobinResponse->fromObj(reset($listObjects));
            }
        } else {
            $astrobinResponse = new $entity();
            $astrobinResponse->fromObj($object);
        }

        return $astrobinResponse;
    }
}
