<?php

namespace Astrobin\Response;

/**
 * Class Image
 * @package Astrobin\Response
 */
class Image extends AbstractResponse
{
    public $title;
    public $subjects;
    public $description;
    public $uploaded;
    public $url_gallery;
    public $url_thumb;
    public $url_regular;
    public $url_hd;
    public $user;


    /**
     * @return void
     */
    public function getUploaded()
    {
        /** @var \DateTime $uploadedFormat */
        $uploadedFormat = \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded);
        $this->uploaded = $uploadedFormat->format('Y-m-d H:i:s');
    }
}
