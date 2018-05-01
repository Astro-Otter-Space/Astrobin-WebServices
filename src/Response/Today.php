<?php

namespace Astrobin\Response;

use Astrobin\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class Today
 * @package Astrobin\Response
 */
class Today extends AbstractResponse implements \IteratorAggregate
{
    public $date;
    public $resource_uri;
    public $listImages;

    /**
     * @return ImageIterator|Traversable
     */
    public function getIterator()
    {
        return new ImageIterator($this->listImages);
    }


    /**
     * @param Image $image
     * @return void
     */
    public function add(Image $image)
    {
        $this->listImages[] = $image;
    }
}