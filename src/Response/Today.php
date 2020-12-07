<?php
declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Response\Iterators\ImageIterator;
use Traversable;

/**
 * Class Today
 * @package Astrobin\Response
 */
final class Today extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{
    public $date;
    public $resource_uri;
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
     * @return void
     */
    public function add(Image $image): void
    {
        $this->listImages[] = $image;
    }
}
