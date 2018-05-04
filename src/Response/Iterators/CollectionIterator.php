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
     * @return mixed|void
     */
    public function next()
    {
        $var = next($this->var);
        return $var;
    }

    /**
     * @return mixed
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
