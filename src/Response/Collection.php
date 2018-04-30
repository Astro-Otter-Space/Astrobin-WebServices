<?php

namespace Astrobin\Response;
use Traversable;

/**
 * Class Collection
 * @package Astrobin\Response
 */
class Collection extends AbstractResponse implements \IteratorAggregate
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
    public function getIterator()
    {
        return new ImageIterator($this->listImages);
    }

    /**
     * @param Image $image
     */
    public function add(Image $image)
    {
        $this->listImages[] = $image;
    }


    /**
     * @param $date_created
     * @return $this
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = \DateTime::createFromFormat('Y-m-d', $date_created);
        return $this;
    }


    /**
     * @param $date_updated
     * @return $this
     */
    public function setDateUpdated($date_updated)
    {
        $this->date_updated = \DateTime::createFromFormat('Y-m-d', $date_updated);
        return $this;
    }
}