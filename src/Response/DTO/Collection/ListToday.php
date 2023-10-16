<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Collection;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Item\Today;

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
