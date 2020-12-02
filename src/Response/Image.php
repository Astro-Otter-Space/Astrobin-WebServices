<?php

namespace AstrobinWs\Response;

/**
 * Class Image
 * @package Astrobin\Response
 */
final class Image extends AbstractResponse
{
    /** @var string */
    public $title;
    /** @var string */
    public $subjects;
    /** @var string */
    public $description;
    /** @var string */
    public $uploaded;
    /** @var string */
    public $url_gallery;
    /** @var string */
    public $url_thumb;
    /** @var string */
    public $url_regular;
    /** @var string */
    public $url_hd;
    /** @var string */
    public $user;
    /** @var string */
    public $url_histogram;
    /** @var string */
    public $url_skyplot;

    /**
     * @return bool|\DateTime
     */
    public function getUploaded(): string
    {
        /** @var \DateTime $uploadedFormat */
        return \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded);
    }
}
