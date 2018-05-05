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
        $uploaded = \DateTime::createFromFormat(DATE_ATOM, $this->uploaded);
        $this->uploaded = $uploaded->format('Y-m-d H:i:s');
    }
}
