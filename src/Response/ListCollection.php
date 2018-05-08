<?php

namespace Astrobin\Response;

use Astrobin\Response\Iterators\CollectionIterator;

/**
 * Class ListCollection
 * @package Astrobin\Response
 */
class ListCollection extends AbstractResponse implements \IteratorAggregate
{

    public $listCollection;

    public function getIterator()
    {
        return new CollectionIterator($this->listCollection);
    }

    /**
     * @param $collection
     */
    public function add($collection)
    {
        $this->listCollection[] = $collection;
    }
}
