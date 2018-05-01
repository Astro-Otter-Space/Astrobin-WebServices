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

    private $listImages = [];
    private $count = 0;

    /**
     * @return ImageIterator|Traversable
     */
    public function getIterator()
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