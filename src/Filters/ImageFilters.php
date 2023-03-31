<?php

namespace AstrobinWs\Filters;

enum ImageFilters: string
{
    use EnumToArray;

    case SUBJECTS_FILTER = 'subjects';

    case USER_FILTER = 'user';

    case TITLE_CONTAINS_FILTER = 'title__icontains';

    case DESC_CONTAINS_FILTER = 'description__icontains';

    case STARTS_WIDTH_FILTER = '__startswith';

    case ENDS_WITH_FILTER = '__endswith';

    case CONTAINS_FILTER = '__contains';

    case IS_STARTS_WIDTH_FILTER = '__istartswith';

    case IS_ENDS_WITH_FILTER = '__iendswith';
}
