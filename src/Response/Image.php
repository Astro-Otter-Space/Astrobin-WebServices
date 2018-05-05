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
     * @param $uploaded
     * @return $this
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = \DateTime::createFromFormat(DATE_ATOM, $uploaded);
        return $this;
    }
}
