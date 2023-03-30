<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class Today
 * @package Astrobin\Response
 */
final class Today extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public $date;

    public Image $image;
    public string $resource_uri;
    public array $listImages;

    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    public function add(Image $image): void
    {
        $this->listImages[] = $image;
    }
}
