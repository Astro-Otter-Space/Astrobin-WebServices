<?php
namespace Astrobin;

use Astrobin\Exceptions\WsException;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AbstractWebService
{
    public const ASTROBIN_URL = 'https://www.astrobin.com/api/v1/';
    public const MAX_REDIRS = 10;
    public const LIMIT_MAX = 20;
    public const TIMEOUT = 30;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';

    protected $timeout;
    private $apiKey;
    private $apiSecret;
    /** @var CurlHttpRequestInterface */
    protected $curlRequest;

    /**
     * AbstractWebService constructor.
     */
    public function __construct()
    {
        $this->apiKey = getenv('ASTROBIN_API_KEY');
        $this->apiSecret = getenv('ASTROBIN_API_SECRET');
        $this->timeout = self::TIMEOUT;
    }

    /**
     * @param string $endPoint
     * @param string $method
     * @param array|null $data
     * @param int|null $id
     *
     * @return mixed|null
     * @throws WsException
     */
    protected function call(string $endPoint, string $method, ?array $data, ?int $id)
    {
        if (is_null($this->apiKey) || is_null($this->apiSecret)) {
            throw new WsException("Astrobin Webservice : API key or API secret are null", 500, null);
        }

        if (!is_null($data) && is_null($id)) {
            $urlAstrobin = $this->buildUrl($endPoint, $data, null);
        } elseif (is_null($data) && !is_null($id)) {
            $urlAstrobin = $this->buildUrl($endPoint, null, $id);
        }

        /** @var CurlHttpRequestInterface curlRequest */
        $this->curlRequest = new CurlHttpRequest();
        $options = $this->initCurlOptions($method, $urlAstrobin);
        $this->curlRequest->setOptionArray($options);

        if (!$resp = $this->curlRequest->execute()) {
            if (empty($resp)) {
                $dataErr = (!is_array($data)) ? [$data] : $data;
                throw new WsException(sprintf("[Astrobin Response] Empty response from \"%s\", check data : %s", $endPoint, implode(' . ', $dataErr)), 500, null);
            }
            // show problem and throw exception
            throw new WsException(
                sprintf("[Astrobin Response] HTTP Error (curl_exec) #%u: %s", $this->curlRequest->getErrNo(), $this->curlRequest->getError()),
                500,
                null
            );
        }
        $this->curlRequest->close();

        if (!$resp || empty($resp)) {
            throw new WsException("[Astrobin Response] Empty Json", 500, null);
        }

        return $this->buildResponse($resp);
    }


    /**
     * Build the WebService URL
     *
     * @param string $endPoint
     * @param array $data
     * @param int $id
     *
     * @return string
     */
    private function buildUrl(string $endPoint, array $data, int $id): string
    {
        $url = self::ASTROBIN_URL . $endPoint;

        if (!is_null($data) && 0 < count($data)) {
            $paramData = implode('&', array_map(static function ($k, $v) {
                $formatValue = "%s";
                if (is_numeric($v)) {
                    $formatValue = "%d";
                }
                return sprintf("%s=$formatValue", $k, $v);
            }, array_keys($data), $data));

            $url .= '?' . $paramData;
        } elseif (!is_null($id)){
            if ('/' !== substr($url, strlen($url)-1, strlen($url))) {
                $url .= '/';
            }
            // Warning : the "/" before "?" is mandatory or else no response from WS...
            $url .= $id . '/?';
        }

        // Add keys and format
        $params = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        $httpParams = implode('', array_map(static function ($k, $v) {
            return sprintf("&%s=%s", $k, $v);
        }, array_keys($params), $params));
        $url .= $httpParams;

        return $url;
    }


    /**
     * Options for cURL request
     *
     * @param $method
     * @param string $url
     * @return mixed
     */
    protected function initCurlOptions(string $method, string$url): array
    {
        // Options CURL
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => self::MAX_REDIRS,
            CURLOPT_HEADER => "Accept:application/json",
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true
        ];

        // GET
        if (self::METHOD_GET === $method) {
            $options = array_replace_recursive($options, [
                CURLOPT_CUSTOMREQUEST => self::METHOD_GET,
                CURLOPT_HTTPGET => true,
            ]);
        }

        return $options;
    }


    /**
     * Check response and jsondecode object
     *
     * @param $resp
     * @return mixed|null
     * @throws WsException
     */
    public function buildResponse(string $resp)
    {
        $obj = null;

        if (is_string($resp)) {
            if (false === strpos($resp, '{', 0)) {
                // check if html
                if (false !== strpos($resp, '<html', 0)) {
                    throw new WsException(sprintf("[Astrobin Response] Response in HTML format :\n %s", $resp), 500, null);
                }
                throw new WsException(sprintf("[Astrobin Response] Not a JSON valid format :\n %s", $resp), 500, null);
            }
            $obj = json_decode($resp, false);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new WsException(
                    sprintf("[Astrobin ERROR] Error JSON :\n%s", json_last_error()),
                    500,
                    null
                );
            }
            if (array_key_exists('error', $obj)) {
                throw new WsException(
                    sprintf("[Astrobin ERROR] Response : %s", $obj->error),
                    500,
                    null
                );
            }
        } else {
            throw new WsException("[Astrobin ERROR] Response is not a string, got ". gettype($resp) . " instead.", 500, null);
        }

        return $obj;
    }
}
