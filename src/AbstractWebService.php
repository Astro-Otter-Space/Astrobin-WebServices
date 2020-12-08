<?php

declare(strict_types=1);

namespace AstrobinWs;

use AstrobinWs\Exceptions\WsException;
use GuzzleHttp\Client;
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
     * Build EndPoint
     *
     * @param string|null $param
     *
     * @return string
     */
    private function buildEndpoint(?string $param): string
    {
        return (!is_null($param)) ? sprintf('/api/v1/%s/%s', $this->getEndPoint(), $param) : $this->getEndPoint();
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
            throw new WsException("Astrobin Webservice : API key or API secret are null", 500, null);
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

        try {
            /**
             * @var ResponseInterface $responseGuzzle
             */
            $responseGuzzle = $this->client->request($method, $endPoint, $options);
        } catch (GuzzleException $e) {
            //throw
            $responseGuzzle = null;
            throw new WsException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->getResponse($responseGuzzle);
    }


    /**
     * Check response and jsondecode object
     *
     * @param ResponseInterface $response
     *
     * @return mixed|null
     * @throws WsException
     * @throws \JsonException
     */
    public function getResponse(ResponseInterface $response): string
    {
        if (200 !== $response->getStatusCode()) {
            throw new WsException(sprintf('[Astrobin Response] Error response: %s', $response->getReasonPhrase()), 500, null);
        }

        /**
         * @var StreamInterface $body
        */
        $body = $response->getBody();

        if (false === $body->isReadable()) {
            throw new WsException("[Astrobin Response] Response not readable", 500, null);
        }

        if (0 === $body->getSize()) {
            throw new WsException(sprintf("[Astrobin Response] Empty response from endPoint \"%s\"", $this->getEndPoint()), 500, null);
        }

        $contents = $body->getContents();
        if (false === strpos($contents, '{', 0)) {
            throw new WsException(sprintf("[Astrobin Response] Not a JSON valid format :\n %s", (string)$body), 500, null);
        }

        return $contents;
    }

    /**
     * Convert string into Json
     *
     * @param string $contents
     *
     * @return \stdClass
     */
    protected function deserialize(string $contents): \stdClass
    {
        try {
            return json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
    }
}
