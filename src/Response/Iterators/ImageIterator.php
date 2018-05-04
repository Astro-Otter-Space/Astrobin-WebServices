<?php

namespace Astrobin\Response\Iterators;

/**
 * Class ImageIterator
 * @package Astrobin\Response
 */
class ImageIterator implements \Iterator
{

    private $var = [];

    /**
     * ImageIterator constructor.
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
     * @return mixed|void
     */
    public function next()
    {
        $var = next($this->var);
        return $var;
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        $var = key($this->var);
        return $var;
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
     * @return mixed|void
     */
    public function rewind()
    {
        $var = reset($this->var);
        return $var;
    }
}
