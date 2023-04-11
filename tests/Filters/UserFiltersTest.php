<?php

namespace Filters;

use AstrobinWs\Filters\UserFilters;
use PHPUnit\Framework\TestCase;

class UserFiltersTest extends TestCase
{
    public function testEnumsAsArray(): void
    {
        $enumValues = UserFilters::toArray();
        $randomString = bin2hex(random_bytes(10));
        $this->assertNotContains($randomString, $enumValues);
        $this->assertContains('username', $enumValues);
    }

    public function testEnumValues(): void
    {
        $this->assertEquals('username', UserFilters::USERNAME_FILTER->value);
    }
}
