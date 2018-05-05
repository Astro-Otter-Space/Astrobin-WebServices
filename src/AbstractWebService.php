<?php

namespace Astrobin;

use Astrobin\Exceptions\WsException;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AbstractWebService
{
    const ASTROBIN_URL = 'https://www.astrobin.com/api/v1/';
    const MAX_REDIRS = 10;
    const LIMIT_MAX = 20;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    protected $timeout;
    private $apiKey;
    private $apiSecret;


    /**
     * AbstractWebService constructor.
     * @param $apiKey
     * @param $apiSecret
     */
    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->timeout = 30;
    }


    /**
     * @param $endPoint
     * @param $method
     * @param $data
     * @return mixed|null
     * @throws WsException
     */
    protected function call($endPoint, $method, $data)
    {
        if (is_null($this->apiKey) || is_null($this->apiSecret)) {
            throw new WsException(sprintf("Astrobin Webservice : API key or API secret is null"));
        }

        $obj = null;
        $curl = $this->initCurl($endPoint, $method, $data);

        // Astrobin dont send data like content_type, http_code : curl_getinfo is totally empty/
//        $respHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (!$resp = curl_exec($curl)) {
            if (empty($resp)) {
                throw new WsException(sprintf("[Astrobin Response] Empty response :\n %s", $resp));
            }
            // show problem and throw exception
            throw new WsException(
                sprintf("[Astrobin Response] HTTP Error (curl_exec) #%u: %s", curl_errno($curl), curl_error($curl))
            );
        }

        curl_close($curl);

        if (!$resp || empty($resp)) {
            throw new WsException("[Astrobin Response] Empty Json");
        }

        if (is_string($resp)) {
            if (false === strpos($resp, '{', 0)) {
                // check if html
                if (false !== strpos($resp, '<html', 0)) {
                    throw new WsException(sprintf("[Astrobin Response] Response in HTML format :\n %s", $resp));
                }
                throw new WsException(sprintf("[Astrobin Response] Not a JSON valid format :\n %s", $resp));
            }
            $obj = json_decode($resp);
            if (JSON_ERROR_NONE != json_last_error()) {
                throw new WsException(
                    sprintf("[Astrobin ERROR] Error JSON :\n%s", json_last_error())
                );
            }
            if (array_key_exists('error', $obj)) {
                throw new WsException(
                    sprintf("[Astrobin ERROR] Response : %s", $obj->error)
                );
            }
        } else {
            throw new WsException("[Astrobin ERROR] Response is not a string, got ". gettype($resp) . " instead.");
        }

        return $obj;
    }


    /**
     * Build cURL URL
     * @param $endPoint
     * @param $method
     * @param $data
     * @return resource
     */
    private function initCurl($endPoint, $method, $data)
    {
        // Build URL with params
        $url = self::ASTROBIN_URL . $endPoint;
        if (is_array($data) && 0 < count($data)) {
            $paramData = implode('&', array_map(function ($k, $v) {
                $formatValue = "%s";
                if (is_numeric($v)) {
                    $formatValue = "%d";
                }
                return sprintf("%s=$formatValue", $k, $v);
            }, array_keys($data), $data));

            $url .= '?' . $paramData;
        } else {
            if ('/' !== substr($url, strlen($url)-1, strlen($url))) {
                $url .= '/';
            }
            // Warning : the "/" before "?" is mandatory or else no response from WS...
            $url .= $data . '/?';
        }

        // Add keys and format
        $params = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        $url .= implode('', array_map(function ($k, $v) {
            return sprintf("&%s=%s", $k, $v);
        }, array_keys($params), $params));

        $curl = curl_init();

        // Options CURL
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => self::MAX_REDIRS,
            CURLOPT_HEADER => "Accept:application/json", //false,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        // GET
        if (self::METHOD_GET === $method) {
            array_merge($options, [
                CURLOPT_CUSTOMREQUEST => self::METHOD_GET,
                CURLOPT_HTTPGET => true,
            ]);
        }
        curl_setopt_array($curl, $options);

        return $curl;
    }
}
