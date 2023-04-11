<?php

namespace Filters;

use AstrobinWs\Filters\ImageFilters;
use PHPUnit\Framework\TestCase;

class ImageFiltersTest extends TestCase
{

    public function testEnumsAsArray(): void
    {
        $enumValues = ImageFilters::toArray();
        $randomString = bin2hex(random_bytes(10));
        $this->assertNotContains($randomString, $enumValues);
        $this->assertNotContains('subject', $enumValues);
        $this->assertNotContains('users', $enumValues);
        $this->assertNotContains('title', $enumValues);
        $this->assertNotContains('title__contains', $enumValues);
        $this->assertNotContains('title__contain', $enumValues);
        $this->assertNotContains('description__icontain', $enumValues);
        $this->assertNotContains('description__contain', $enumValues);
        $this->assertNotContains('description__contains', $enumValues);
        $this->assertNotContains('__startwith', $enumValues);
        $this->assertNotContains('startswith', $enumValues);
        $this->assertNotContains('startwith', $enumValues);
        $this->assertNotContains('__endwith', $enumValues);
        $this->assertNotContains('endswith', $enumValues);
        $this->assertNotContains('endwith', $enumValues);
        $this->assertNotContains('__contain', $enumValues);
        $this->assertNotContains('contains', $enumValues);
        $this->assertNotContains('contain', $enumValues);
    }

    public function testEnumValues(): void
    {
        $this->assertEquals('subjects', ImageFilters::SUBJECTS_FILTER->value);
        $this->assertEquals('user', ImageFilters::USER_FILTER->value);
        $this->assertEquals('title__icontains', ImageFilters::TITLE_CONTAINS_FILTER->value);
        $this->assertEquals('description__icontains', ImageFilters::DESC_CONTAINS_FILTER->value);
        $this->assertEquals('__startswith', ImageFilters::STARTS_WIDTH_FILTER->value);
        $this->assertEquals('__endswith', ImageFilters::ENDS_WITH_FILTER->value);
        $this->assertEquals('__contains', ImageFilters::CONTAINS_FILTER->value);
        $this->assertEquals('__istartswith', ImageFilters::IS_STARTS_WIDTH_FILTER->value);
        $this->assertEquals('__iendswith', ImageFilters::IS_ENDS_WITH_FILTER->value);
    }
}
