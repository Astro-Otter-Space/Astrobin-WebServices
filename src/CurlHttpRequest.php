<?php

namespace Astrobin;

/**
 * Class CurlHttpRequest
 * @package Astrobin
 */
class CurlHttpRequest implements CurlHttpRequestInterface
{
    private $handle;

    /**
     * CurlHttpRequest constructor.
     * @param $url
     */
    public function __construct()
    {
        $this->handle = curl_init();
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    /**
     * @param $options
     */
    public function setOptionArray($options)
    {
        curl_setopt_array($this->handle, $options);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return curl_exec($this->handle);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return curl_error($this->handle);
    }

    /**
     * @return int
     */
    public function getErrNo()
    {
        return curl_errno($this->handle);
    }


    /**
     *
     */
    public function close()
    {
        curl_close($this->handle);
    }
}
