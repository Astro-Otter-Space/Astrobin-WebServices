<?php

namespace Astrobin;

/**
 * Interface CurlHttpRequestInterface
 * @package Astrobin
 */
interface CurlHttpRequestInterface
{
    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setOption($name, $value);

    /**
     * @param $options
     * @return mixed
     */
    public function setOptionArray($options);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo($name);

    /**
     * @return mixed
     */
    public function close();

    /**
     * @return mixed
     */
    public function getError();

    /**
     * @return mixed
     */
    public function getErrNo();
}
