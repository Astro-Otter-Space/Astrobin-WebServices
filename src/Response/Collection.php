<?php

namespace Astrobin\Response;
use Astrobin\AbstractWebService;
use Traversable;

/**
 * Class Collection
 * @package Astrobin\Response
 */
class Collection extends AbstractWebService implements \IteratorAggregate
{

    public $id;
    public $name;
    public $description;
    public $user;
    public $date_created;
    public $date_updated;
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