<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Response\AstrobinResponse;

/**
 * Class Location
 *
 * @package Astrobin\Response
 */
final class Location extends AbstractResponse implements AstrobinResponse
{
    public $name;
    public $city;
    public $country;
    public $state;
    public $altitude;
}
