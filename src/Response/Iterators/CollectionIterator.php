<?php

declare(strict_types=1);

namespace AstrobinWs\Response\Iterators;

/**
 * Class CollectionIterator
 * @package Astrobin\Response\Iterators
 */
class CollectionIterator implements \Iterator
{
    private array $var;

    /**
     * CollectionIterator constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->var = $array;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->var);
    }


    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->var);
    }

    /**
     * @return mixed
     */
    public function key()
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
    public function rewind()
    {
        return reset($this->var);
    }
}
