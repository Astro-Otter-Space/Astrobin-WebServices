<?php

namespace AstrobinWs\Response\Iterators;

/**
 * Class ImageIterator
 * @package Astrobin\Response
 */
final class ImageIterator implements \Iterator
{
    /** @var array  */
    private $var = [];

    /**
     * ImageIterator constructor.
     * @param $array
     */
    public function __construct(array $array)
    {
        if (is_array($array)) {
            $this->var = $array;
        }
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
     * @return int|mixed|null|string
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
