<?php

namespace AstrobinWs\Services;

/**
 * Interface WsInterface
 * @package Astrobin
 */
interface WsInterface
{
    public function callWithId(int $id);
    public function callWithParams(array $params);
    public function responseWs();
}
