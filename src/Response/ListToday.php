<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use Exception;
use Traversable;

/**
 * Class ListToday
 * @package AstrobinWs\Response
 */
class ListToday extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public $listToday;
    public $count = 0;

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->listToday);
    }

    /**
     * @param Today $today
     */
    public function add(Today $today): void
    {
        $this->count++;
        $this->listToday[] = $today;
    }
}
