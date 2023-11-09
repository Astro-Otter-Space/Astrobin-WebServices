<?php

namespace Response\DTO\Item;

use Collection;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testDateCreatedProperty(): void
    {
        $collection = new \AstrobinWs\Response\DTO\Item\Collection();

        $collection->setDateUpdated(null);
        $this->assertNull($collection->date_created);

        $collection->setDateCreated('aaaa-bb-ccccc');
        $this->assertNull($collection->date_created);

        $collection->setDateCreated('2022-09-22T11:20:22.584072');
        $this->assertInstanceOf(\DateTime::class, $collection->date_created);

        $collection->setDateCreated('2022-09-22');
        $this->assertInstanceOf(\DateTime::class, $collection->date_created);
    }
}
