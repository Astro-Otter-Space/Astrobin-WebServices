<?php

namespace AstrobinWs\Filters;

enum QueryFilters: string
{
    use EnumToArray;

    case LIMIT = 'limit';

    case OFFSET = 'offset';

    case DATE_FORMAT = 'Y-m-d';
}
