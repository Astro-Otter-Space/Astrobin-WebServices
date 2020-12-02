<?php

namespace AstrobinWs;

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
    public function setOption(string $name, string $value);

    /**
     * @param $options
     * @return mixed
     */
    public function setOptionArray(array $options);

    /**
     * @return mixed
     */
    public function execute();

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo(string $name);

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
