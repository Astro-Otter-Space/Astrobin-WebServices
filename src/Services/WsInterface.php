<?php

namespace AstrobinWs\Services;

use AstrobinWs\Response\AstrobinResponse;

/**
 * Interface WsInterface
 *
 * @package Astrobin
 */
interface WsInterface
{
    public function getById(int $id);
    public function buildResponse(string $object): AstrobinResponse;
}
