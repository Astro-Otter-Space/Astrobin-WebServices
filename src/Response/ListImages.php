<?php

namespace Astrobin\Response;

use Astrobin\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class ListImages
 * @package Astrobin\Response
 */
class ListImages implements \IteratorAggregate
{

    public $listImages = [];
    public $count = 0;

    /**
     * @return ImageIterator|Traversable
     */
    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    /**
     * @param Image $image
     */
    public function add(Image $image)
    {
        $this->count++;
        $this->listImages[] = $image;
    }
}
