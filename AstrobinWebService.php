<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 20/04/18
 * Time: 18:34
 */

namespace HamhamFonfon\Astrobin;
use HamhamFonfon\Astrobin\Exceptions\AstrobinException;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AstrobinWebService
{
    const ASTROBIN_URL = 'https://www.astrobin.com/api/v1/';
    const MAX_REDIRS = 10;
    const LIMIT_MAX = 24;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    protected $timeout;
    private $apiKey;
    private $apiSecret;


    /**
     * AstrobinWebService constructor.
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
     * @throws astrobinException
     */
    protected function call($endPoint, $method, $data)
    {
        $obj = null;
        $curl = $this->initCurl($endPoint, $method, $data);
        if(!$resp = curl_exec($curl)) {
            if (empty($resp)) {
                throw new AstrobinException("Empty Json response from Astrobin");
            }
            // show problem, genere exception
            throw new AstrobinException(
                sprintf("HTTP Error (curl_exec) #%u: %s", curl_errno($curl), curl_error($curl))
            );
        }

        // TODO make something with HTTP code...
        $respHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!$resp || empty($resp)) {
            throw new AstrobinException("Empty Json response from Astrobin");
        }

        if (is_string($resp)) {
            if (false === strpos($resp, '{', 0)) {
                // check if html
                if (false !== strpos($resp, '<html', 0)) {
                    throw new AstrobinException(sprintf("Response from Astrobin is in HTML format :\n %s", $resp));
                }
                throw new AstrobinException(sprintf("Response from Astrobin is not a JSON valid format :\n %s", $resp));
            }
            $obj = json_decode($resp);
            if (JSON_ERROR_NONE != json_last_error()) {
                throw new AstrobinException(
                    sprintf("Error JSON from Astrobin :\n%s", json_last_error())
                );
            }
            if (array_key_exists('error', $obj)) {
                throw new AstrobinException(
                    sprintf("Error from Astrobin response : %s", $obj->error)
                );
            }
        } else {
            throw new AstrobinException("Response from Astrobin is not a string, got ". gettype($resp) . " instead.");
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
            $paramData = implode('&', array_map(function($k, $v) {
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
            $url .= $data . '?';
        }

        // Add keys and format
        $params = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        $url .= implode('', array_map(function($k, $v) {
            return sprintf("&%s=%s", $k, $v);
        }, array_keys($params), $params));

        // TODO : using http_build_query() ?
        $curl = curl_init($url);

        // Options CURL
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => self::MAX_REDIRS,
            CURLOPT_HEADER => false,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false
        ];

        // GET
        if ($method === self::METHOD_GET) {
            array_merge($options, [
                CURLOPT_CUSTOMREQUEST => self::METHOD_GET,
                CURLOPT_HTTPGET => true,
            ]);
        }
        curl_setopt_array($curl, $options);
        return $curl;
    }
}