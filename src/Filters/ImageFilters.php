<?php

declare(strict_types=1);

namespace AstrobinWs\Filters;

/**
 * Class ImageFilters
 * @package AstrobinWs\Filters
 */
final class ImageFilters extends AbstractFilters
{
    public const SUBJECTS_FILTER = 'subjects';
    public const USER_FILTER = 'user';
    public const TITLE_CONTAINS_FILTER = 'title__icontains';
    public const DESC_CONTAINS_FILTER = 'description__icontains';
    public const STARTS_WIDTH_FILTER = '__startswith';
    public const ENDS_WITH_FILTER = '__endswith';
    public const CONTAINS_FILTER = '__contains';
    public const IS_STARTS_WIDTH_FILTER = '__istartswith';
    public const IS_ENDS_WITH_FILTER = '__iendswith';
}