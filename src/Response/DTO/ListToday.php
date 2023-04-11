<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

use AstrobinWs\Response\AbstractResponse;

/**
 * Class ListToday
 * @package AstrobinWs\Response
 */
class ListToday extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public array $listToday;
    public int $count = 0;

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->listToday);
    }

    public function add(Today $today): void
    {
        ++$this->count;
        $this->listToday[] = $today;
    }
}