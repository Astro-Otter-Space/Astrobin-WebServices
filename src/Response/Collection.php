<?php

namespace AstrobinWs\Response;

use Astrobin\Response\AstrobinResponse;
use AstrobinWs\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class Collection
 * @package Astrobin\Response
 */
final class Collection extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    /** @var integer */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $user;
    /** @var \DateTime */
    public $date_created;
    /** @var \DateTime */
    public $date_updated;
    public $images;
    /** @var array */
    public $listImages;

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
        $this->date_created = \DateTime::createFromFormat('Y-m-d', $dateCreated);
        return $this;
    }


    /**
     * @param string $dateUpdated
     *
     * @return $this
     */
    public function setDateUpdated(string $dateUpdated): self
    {
        $this->date_updated = \DateTime::createFromFormat('Y-m-d', $dateUpdated);
        return $this;
    }
}
