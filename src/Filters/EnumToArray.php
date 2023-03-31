<?php

namespace AstrobinWs\Filters;

trait EnumToArray
{
    public static function toArray(): array
    {
        return array_map(static fn(self $enum) => $enum->value, self::cases());
    }
}
