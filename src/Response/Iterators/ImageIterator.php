<?php

declare(strict_types=1);

namespace AstrobinWs\Response\Iterators;

use ReturnTypeWillChange;

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

    #[ReturnTypeWillChange]
    public function next(): mixed
    {
        return next($this->var);
    }

    /**
     * @return string|int|null
     */
    public function key(): string|int|null
    {
        return key($this->var);
    }

    public function valid(): bool
    {
        $key = key($this->var);
        return !is_null($key) && false !== $key;
    }

    /**
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function rewind(): mixed
    {
        return reset($this->var);
    }
}
