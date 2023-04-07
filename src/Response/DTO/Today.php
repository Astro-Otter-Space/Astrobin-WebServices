<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\Iterators\ImageIterator;

/**
 * Class Today
 * @package Astrobin\Response
 */
final class Today extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public string $date;
    /**
     * Path of image, not Image instance
     * @var string
     */
    public string $image;
    public string $resource_uri;
    public ?array $listImages;

    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    public function add(Image $image): void
    {
        $this->listImages[] = $image;
    }
}
