<?php

namespace Filters;

use AstrobinWs\Filters\CollectionFilters;
use AstrobinWs\Filters\UserFilters;
use PHPUnit\Framework\TestCase;

class CollectionFiltersTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEnumsAsArray(): void
    {
        $randomString = bin2hex(random_bytes(10));
        $enumValues = CollectionFilters::toArray();
        $this->assertNotContains($randomString, $enumValues);
        $this->assertNotContains('username', $enumValues);
        $this->assertNotContains('users', $enumValues);
        $this->assertNotContains('u', $enumValues);
    }

    public function testEnumValues(): void
    {
        $this->assertEquals('user', CollectionFilters::USER_FILTER->value);
    }
}
