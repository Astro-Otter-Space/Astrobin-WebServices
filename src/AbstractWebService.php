<?php

declare(strict_types=1);

namespace AstrobinWs;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\AstrobinError;
use AstrobinWs\Response\AstrobinResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Class AstrobinWebService
 *
 * @package AppBundle\Astrobin
 */
abstract class AbstractWebService
{
    final public const LIMIT_MAX = 20;
    final public const TIMEOUT = 30;

    protected int $timeout = self::TIMEOUT;

    protected static array $headers = [
        'Accept' => GuzzleSingleton::APPLICATION_JSON,
        'Content-Type' => GuzzleSingleton::APPLICATION_JSON
    ];

    /**
     * @var Client
     */
    private Client $client;

    /**
     * AbstractWebService constructor.
     */
    public function __construct(
        protected ?string $apiKey,
        protected ?string $apiSecret
    ) {
        $this->buildFactory();
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getApiSecret(): string
    {
        return $this->apiSecret;
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
     */
    abstract protected function getObjectEntity(): ?string;

    /**
     * Get instance of response collection entity
     */
    abstract protected function getCollectionEntity(): ?string;

    /**
     * Build EndPoint
     */
    private function buildEndpoint(?string $param): string
    {
        return (is_null($param)) ? sprintf('/api/v1/%s', $this->getEndPoint()) : sprintf('/api/v1/%s/%s', $this->getEndPoint(), $param);
    }

    /**
     * @throws WsException
     * @throws JsonException
     */
    protected function get(?string $id, ?array $queryParams): ?string
    {
        return $this->buildRequest($id, null, $queryParams, null, GuzzleSingleton::METHOD_GET);
    }

    /**
     * NOT USED, just for example
     */
    protected function post(string $id, ?array $queryParams, ?array $body): ?string
    {
        try {
            return $this->buildRequest($id, $body, $queryParams, null, GuzzleSingleton::METHOD_POST);
        } catch (WsException | JsonException) {
        }
        return null;
    }

    /**
     * Build guzzle client request
     * @throws WsException | JsonException
     */
    private function buildRequest(
        ?string $id,
        ?array $body,
        ?array $queryParams,
        ?array $headers,
        string $method
    ): ?string {
        $options = [];
        if (!$this->apiKey || !$this->apiSecret) {
            throw new WsException(WsException::KEYS_ERROR, 500, null);
        }

        $endPoint = $this->buildEndpoint($id);
        $options['headers'] = is_null($headers) ? self::$headers : array_merge(self::$headers, $headers);

        $astrobinParams = ['api_key' => $this->apiKey, 'api_secret' => $this->apiSecret, 'format' => 'json'];
        if (is_null($queryParams)) {
            $queryParams = [];
        }

        $options = [
            'query' => array_filter(array_merge($astrobinParams, $queryParams))
        ];

        if (!empty($body)) {
            $options['body'] = $body;
        }

        $responseGuzzle = $msgErr = null;
        try {
            $responseGuzzle = $this->client->request($method, $endPoint, $options);
        } catch (GuzzleException $guzzleException) {
            $msgErr = $guzzleException->getMessage();
        }

        if ($responseGuzzle instanceof ResponseInterface) {
            return $this->getResponse($responseGuzzle);
        }

        throw new WsException($msgErr, 500, null);
    }


    /**
     * Check response and jsondecode object
     * @throws WsException|JsonException
     */
    public function getResponse(ResponseInterface $response): string
    {
        if (200 !== $response->getStatusCode()) {
            throw new WsException(sprintf(WsException::GUZZLE_RESPONSE, $response->getReasonPhrase()), 500, null);
        }

        $body = $response->getBody();
        if (!$body->isReadable()) {
            throw new WsException(WsException::ERR_READABLE, 500, null);
        }

        if (is_null($body->getSize()) || 0 === $body->getSize()) {
            throw new WsException(sprintf(WsException::ERR_EMPTY, $this->getEndPoint()), 500, null);
        }

        $contents = $body->getContents();
        if (!str_contains($contents, '{')) {
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
     * @throws JsonException
     * @throws WsResponseException
     */
    protected function deserialize(string $contents): stdClass
    {
        $responseJson = json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        if (
            property_exists($responseJson, "objects")
            && property_exists($responseJson, "meta")
            && 0 === $responseJson->meta->total_count
        ) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        return $responseJson;
    }

    /**
     * Build response from WebService Astrobin
     */
    protected function buildResponse(string $response): ?AstrobinResponse
    {
        try {
            $object = $this->deserialize($response);
        } catch (WsResponseException | JsonException $e) {
            return new AstrobinError($e->getMessage());
        }

        $entity = $this->getObjectEntity();
        $collectionEntity = $this->getCollectionEntity();

        if (property_exists($object, "objects") && 0 < (is_countable($object->objects) ? count($object->objects) : 0)) {
            $listObjects = $object->objects;
            if (1 < (is_countable($listObjects) ? count($listObjects) : 0)) {
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
