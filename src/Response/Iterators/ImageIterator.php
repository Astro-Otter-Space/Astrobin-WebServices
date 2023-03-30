<?php

declare(strict_types=1);

namespace AstrobinWs\Response\Iterators;

/**
 * Class ImageIterator
 * @package Astrobin\Response
 */
final class ImageIterator implements \Iterator
{
    /**
     * ImageIterator constructor.
     */
    public function __construct(private array $var)
    {
    }

    public function current(): mixed
    {
        return current($this->var);
    }


    public function next(): mixed
    {
        return next($this->var);
    }

    /**
     * @return int|string|null
     */
    public function key(): mixed
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
    public function rewind(): mixed
    {
        return reset($this->var);
    }
}
