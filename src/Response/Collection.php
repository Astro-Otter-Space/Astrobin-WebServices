<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Filters\AbstractFilters;
use AstrobinWs\Filters\CollectionFilters;
use AstrobinWs\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class Collection
 * @package Astrobin\Response
 */
final class Collection extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public int $id;
    public string $name;
    public ?string $description;
    public string $user;
    public string $date_created;
    public string $date_updated;
    public ?array $images;
    public ?array $listImages;

    /**
     * @return ImageIterator|Traversable
     */
    public function getIterator(): ImageIterator
    {
        return new ImageIterator($this->listImages);
    }

    /**
     * @param Image $image
     */
    public function add(Image $image): void
    {
        $this->listImages[] = $image;
    }

    /**
     * @param string $dateCreated
     *
     * @return $this
     */
    public function setDateCreated(string $dateCreated): self
    {
        $this->date_created = \DateTime::createFromFormat(AbstractFilters::DATE_FORMAT, $dateCreated);
        return $this;
    }

    /**
     * @param string $dateUpdated
     *
     * @return $this
     */
    public function setDateUpdated(string $dateUpdated): self
    {
        $this->date_updated = \DateTime::createFromFormat(AbstractFilters::DATE_FORMAT, $dateUpdated);
        return $this;
    }
}
