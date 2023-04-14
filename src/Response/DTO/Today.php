<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\Iterators\ImageIterator;

/**
 * Class Today
 * @package Astrobin\Response
 */
final class Today extends AbstractResponse implements AstrobinResponse
{
    public string $date;
    /**
     * Path of image, not Image instance
     * @var string
     */
    public Image|string|null $image;
    public string $resource_uri;

}
