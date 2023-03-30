<?php

declare(strict_types=1);

namespace AstrobinWs\Response\Iterators;

use ReturnTypeWillChange;

/**
 * Class CollectionIterator
 * @package Astrobin\Response\Iterators
 */
class CollectionIterator implements \Iterator
{

    /**
     * CollectionIterator constructor.
     */
    public function __construct(private array $var)
    {
    }

    /**
     * @return mixed
     */
    #[ReturnTypeWillChange] public function current(): mixed
    {
        return current($this->var);
    }


    #[ReturnTypeWillChange] public function next(): mixed
    {
        return next($this->var);
    }


    public function key(): string|int|null
    {
        return key($this->var);
    }


    /**
     * @return bool
     */
    public function valid(): bool
    {
        $key = key($this->var);
        return !is_null($key) && false !== $key;
    }

    /**
     * @return mixed
     */
    #[ReturnTypeWillChange] public function rewind(): mixed
    {
        return reset($this->var);
    }
}
