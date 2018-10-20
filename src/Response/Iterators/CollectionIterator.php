<?php

namespace Astrobin\Response\Iterators;

/**
 * Class CollectionIterator
 * @package Astrobin\Response\Iterators
 */
class CollectionIterator implements \Iterator
{
    private $var = [];


    /**
     * CollectionIterator constructor.
     * @param $array
     */
    public function __construct($array)
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
        $var = current($this->var);
        return $var;
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
    public function valid()
    {
        $key = key($this->var);
        $var = (!is_null($key) && false !== $key);
        return $var;
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->var);
    }
}
