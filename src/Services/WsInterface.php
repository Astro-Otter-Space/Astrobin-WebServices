<?php

declare(strict_types=1);

namespace AstrobinWs\Services;

use AstrobinWs\Response\DTO\AstrobinResponse;

/**
 * Interface WsInterface
 *
 * @package Astrobin
 */
interface WsInterface
{
    public function getById(?string $id): ?AstrobinResponse;
}
