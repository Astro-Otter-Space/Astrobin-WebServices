<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class ListImages
 * @package Astrobin\Response
 */
final class ListImages implements \IteratorAggregate, AstrobinResponse
{
    public array $listImages = [];
    public int $count = 0;

    /**
     * @return ImageIterator|Traversable
     */
    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    public function add(Image $image): void
    {
        $this->count++;
        $this->listImages[] = $image;
    }
}
