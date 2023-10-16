<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Collection;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\Iterators\CollectionIterator;

/**
 * Class ListCollection
 * @package Astrobin\Response
 */
final class ListCollection extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public array $listCollection;

    /**
     * @return CollectionIterator
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
