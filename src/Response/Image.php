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
     * @return bool|\DateTime
     */
    public function getUploaded()
    {
        /** @var \DateTime $uploadedFormat */
        $this->uploaded = \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded);
        return $this->uploaded;
    }
}
