<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 30/04/18
 * Time: 19:23
 */

namespace Astrobin\Response;

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
//        return new \ArrayIterator($this->listImages);
    }

    /**
     * @param $image
     */
    public function add($image)
    {
        $this->listImages[$this->count++] = $image;
    }
}