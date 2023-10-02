<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Item;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;

/**
 * Class Today
 * @package Astrobin\Response
 */
final class Today extends AbstractResponse implements AstrobinResponse
{
    public string $date;
    /**
     * Path of image, not Image instance
     */
    public string|Image|null $image = null;
    public string $resource_uri;

}
