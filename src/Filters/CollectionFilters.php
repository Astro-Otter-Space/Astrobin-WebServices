<?php

declare(strict_types=1);

namespace AstrobinWs\Filters;

/**
 * Class CollectionFilters
 * @package AstrobinWs\Filters
 */
enum CollectionFilters: string
{
    use EnumToArray;

    case USER_FILTER = 'username';
}
