<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO\Item;

use AstrobinWs\Filters\QueryFilters;
use AstrobinWs\Response\AbstractResponse;
use AstrobinWs\Response\DTO\AstrobinResponse;
use AstrobinWs\Response\DTO\Collection\ListImages;

/**
 * Class Collection
 * @package Astrobin\Response
 */
final class Collection extends AbstractResponse implements AstrobinResponse
{
    public int $id;

    public string $name;

    public ?string $description = null;


    public string $user;

    public string|\DateTime $date_created;

    public string|\DateTime $date_updated;

    public ListImages|array|null $images = null;

    public function setDateCreated(string $dateCreated): self
    {
        $this->date_created = \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateCreated);
        return $this;
    }

    public function setDateUpdated(string $dateUpdated): self
    {
        $this->date_updated = \DateTime::createFromFormat(QueryFilters::DATE_FORMAT->value, $dateUpdated);
        return $this;
    }
}
