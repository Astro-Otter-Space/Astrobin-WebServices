<?php

namespace Response\DTO\Item;

use AstrobinWs\Response\DTO\Item\Collection;
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
        $collection = new Collection();

        $collection->setDateCreated(null);
        $this->assertNull($collection->date_created);

        $collection->setDateCreated('aaaa-bb-ccccc');
        $this->assertNull($collection->date_created);

        $collection->setDateCreated('2022-09-22T11:20:22.584072');
        $this->assertInstanceOf(\DateTime::class, $collection->date_created);

        $collection->setDateCreated('2022-09-22');
        $this->assertInstanceOf(\DateTime::class, $collection->date_created);
    }

    public function testDateUpdatedProperty(): void
    {
        $collection = new Collection();

        $collection->setDateUpdated(null);
        $this->assertNull($collection->date_updated);

        $collection->setDateUpdated('aaaa-bb-ccccc');
        $this->assertNull($collection->date_updated);

        $collection->setDateUpdated('2022-09-22T11:20:22.584072');
        $this->assertInstanceOf(\DateTime::class, $collection->date_updated);

        $collection->setDateUpdated('2022-09-22');
        $this->assertInstanceOf(\DateTime::class, $collection->date_updated);
    }
}
