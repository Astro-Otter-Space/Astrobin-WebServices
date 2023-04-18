<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Collection;

use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Item\Image;
use AstrobinWs\Response\Iterators\ImageIterator;

/**
 * Class ListImages
 * @package Astrobin\Response
 */
final class ListImages implements \IteratorAggregate, AstrobinResponse
{
    public array $listImages = [];
    public int $count = 0;

    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    public function add(Image $image): void
    {
        ++$this->count;
        $this->listImages[] = $image;
    }
}
