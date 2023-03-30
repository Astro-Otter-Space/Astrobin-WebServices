<?php

declare(strict_types=1);

namespace AstrobinWs\Filters;

/**
 * Class AbstractFilters
 * @package AstrobinWs\Filters
 */
abstract class AbstractFilters
{
    final public const LIMIT = 'limit';
    final public const OFFSET = 'offset';
    final public const DATE_FORMAT = 'Y-m-d';

    public static function getFilters(): array
    {
        return (new \ReflectionClass(static::class))->getConstants();
    }
}
