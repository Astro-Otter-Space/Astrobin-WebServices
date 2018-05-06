<?php

namespace Astrobin;

/**
 * Interface CurlHttpRequestInterface
 * @package Astrobin
 */
interface CurlHttpRequestInterface
{
    public function setOption($name, $value);
    public function setOptionArray($options);
    public function execute();
    public function getInfo($name);
    public function close();
    public function getError();
    public function getErrNo();
}
