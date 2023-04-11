<?php

namespace Filters;

use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Filters\UserFilters;
use MongoDB\Driver\Query;
use PHPUnit\Framework\TestCase;

class QueryFiltersTest extends TestCase
{
    public function testEnumsAsArray(): void
    {
        $enumValues = QueryFilters::toArray();
        $randomString = bin2hex(random_bytes(10));
        $this->assertNotContains('y-m-d', $enumValues);
        $this->assertNotContains('d/m/Y', $enumValues);
        $this->assertNotContains('lim', $enumValues);
        $this->assertNotContains('ofset', $enumValues);
        $this->assertNotContains('limite', $enumValues);
        $this->assertNotContains($randomString, $enumValues);
    }

    public function testEnumValues(): void
    {
        $this->assertEquals('limit', QueryFilters::LIMIT->value);
        $this->assertEquals('offset', QueryFilters::OFFSET->value);
        $this->assertEquals('Y-m-d', QueryFilters::DATE_FORMAT->value);

    }
}
