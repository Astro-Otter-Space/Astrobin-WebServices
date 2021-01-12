<?php

declare(strict_types=1);

namespace AstrobinWs\Filters;

/**
 * Class AbstractFilters
 * @package AstrobinWs\Filters
 */
abstract class AbstractFilters
{
    public const LIMIT = 'limit';
    public const OFFSET = 'offset';

    public static function getFilters(): array
    {
        $reflexionClass = new \ReflectionClass(static::class);
        return $reflexionClass->getConstants();
    }
}