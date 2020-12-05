<?php

namespace AstrobinWs\Response;

use Astrobin\Response\AstrobinResponse;

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
