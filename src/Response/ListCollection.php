<?php

namespace AstrobinWs\Response;

use AstrobinWs\Response\Iterators\CollectionIterator;

/**
 * Class ListCollection
 * @package Astrobin\Response
 */
final class ListCollection extends AbstractResponse implements \IteratorAggregate
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
    public function add($collection): void
    {
        $this->listCollection[] = $collection;
    }
}
