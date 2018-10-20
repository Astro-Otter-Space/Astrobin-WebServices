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

    /**
     * @return CollectionIterator|\Traversable
     */
    public function getIterator(): CollectionIterator
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
