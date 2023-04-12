<?php

declare(strict_types=1);

namespace AstrobinWs;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\Exceptions\WsResponseException;
use AstrobinWs\Response\DTO\AstrobinError;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\EntityFactory;
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

        $guzzleResponse = $msgErr = null;
        try {
            $guzzleResponse = $this->client->request($method, $endPoint, $options);
        } catch (GuzzleException $guzzleException) {
            $msgErr = $guzzleException->getMessage();
        }

        if ($guzzleResponse instanceof ResponseInterface) {
            return $this->checkGuzzleResponse($guzzleResponse);
        }

        throw new WsException($msgErr, 500, null);
    }


    /**
     * Check response and jsondecode object
     * @throws WsException|JsonException
     */
    public function checkGuzzleResponse(ResponseInterface $response): string
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

        if (
            property_exists($jsonContent, "objects")
            && property_exists($jsonContent, "meta")
            && 0 === $jsonContent->meta->total_count
        ) {
            throw new WsResponseException(WsException::RESP_EMPTY, 500, null);
        }

        return $contents;
    }


    /**
     * @throws JsonException
     * @todo get out this method and put it in factory response class
     * Build response from WebService Astrobin
     */
    protected function buildResponse(string $guzzleResponse): ?AstrobinResponse
    {
        $entity = $this->getObjectEntity();
        $collectionEntity = $this->getCollectionEntity();

        $entityFactory = new EntityFactory($guzzleResponse);
        return $entityFactory
            ->setEntity($entity)
            ->setCollectionEntity($collectionEntity)
            ->buildResponse();
    }
}
