<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 14:10
 */

namespace HamhamFonfon\Astrobin\Response;

/**
 * Class AstrobinCollection
 * @package HamhamFonfon\Astrobin\Response
 */
class AstrobinCollection
{

    public $images;

    /**
     * @param $images
     */
    public function setImages($images)
    {
        $listImages = [];
        foreach ($images as $image) {
            $astrobinImage = new AstrobinImage();
            $astrobinImage->fromObj($image);
            $listImages[] = $astrobinImage;
        }
        $this->images = $listImages;
    }


}