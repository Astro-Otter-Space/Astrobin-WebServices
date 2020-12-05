<?php

namespace AstrobinWs\Services;

use Astrobin\Response\AstrobinResponse;

/**
 * Interface WsInterface
 *
 * @package Astrobin
 */
interface WsInterface
{
    public function getById(int $id);
    public function buildResponse(array $objects): AstrobinResponse;
}
