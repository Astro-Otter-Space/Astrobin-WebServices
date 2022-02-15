<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

/**
 * Class Image
 * @package Astrobin\Response
 */
final class Image extends AbstractResponse implements AstrobinResponse
{
    public string $title;
    public string $subjects;
    public string $description;
    public string $uploaded;
    public string $url_gallery;
    public string $url_thumb;
    public string $url_regular;
    public string $url_hd;
    public string $user;
    public string $url_histogram;
    public string $url_skyplot;

    /**
     * @return bool|\DateTime
     */
    public function getUploaded(): \DateTime
    {
        /** @var \DateTime $uploadedFormat */
        return \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded);
    }
}
