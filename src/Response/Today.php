<?php

namespace Astrobin\Response;

/**
 * Class Today
 * @package Astrobin\Response
 */
class Today extends AbstractResponse
{
    public $date;
    public $resource_uri;


    /**
     * @param $image
     * @return $this
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function addImage($image)
    {
        if (is_object($image)) {
            $astrobinImage = new Image();
            $astrobinImage->fromObj($image);

            $this->image = $astrobinImage;
        }
        return $this;
    }
}