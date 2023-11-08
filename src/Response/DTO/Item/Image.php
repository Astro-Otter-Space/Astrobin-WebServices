<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Item;

use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;

/**
 * Class Image
 * @package Astrobin\Response
 */
final class Image extends AbstractResponse implements AstrobinResponse
{
    public ?string $title = null;

    public ?array $subjects;

    public ?string $description = null;

    public string|null $uploaded = null;

    public ?string $url_gallery = null;

    public ?string $url_thumb = null;

    public ?string $url_regular = null;

    public ?string $url_hd = null;

    public ?string $url_solution = null;
    public ?string $url_advanced_solution = null;

    public ?string $url_histogram = null;

    public ?string $url_skyplot = null;

    public ?string $url_advanced_skyplot = null;
    public ?string $url_advanced_skyplot_small = null;

    public ?string $user = null;
    public ?int $views = 0;

    public ?int $likes = 0;


    public function getUploaded(): ?\DateTime
    {
        if (is_null($this->uploaded)) {
            return null;
        }

        try {
            return \DateTime::createFromFormat('Y-m-d\T H:i:s.u', $this->uploaded) ?: null;
        } catch (\Exception) {
            return new \DateTime('now');
        }
    }
}
