<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\Iterators\ImageIterator;

/**
 * Class Collection
 * @package Astrobin\Response
 */
final class Collection extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public int $id;
    public string $name;
    public ?string $description = null;

    public string $user;
    public string|\DateTime $date_created;
    public string|\DateTime $date_updated;
    public ?array $images;
    public ?array $listImages;

    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }


    public function add(Image $image): void
    {
        $this->listImages[] = $image;
    }

    public function setDateCreated(string $dateCreated): self
    {
        $this->date_created = \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateCreated);
        return $this;
    }

    public function setDateUpdated(string $dateUpdated): self
    {
        $this->date_updated = \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateUpdated);
        return $this;
    }
}
