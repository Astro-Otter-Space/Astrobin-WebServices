<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

/**
 * Class Image
 * @package Astrobin\Response
 */
final class Image extends AbstractResponse implements AstrobinResponse
{
    public ?string $title = null;
    public ?array $subjects;
    public ?string $description = null;
    public ?string $uploaded = null;
    public ?string $url_gallery = null;
    public ?string $url_thumb = null;
    public ?string $url_regular = null;
    public ?string $url_hd = null;
    public ?string $user = null;
    public ?string $url_histogram = null;
    public ?string $url_skyplot = null;

    public function getUploaded(): \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded);
    }
}
