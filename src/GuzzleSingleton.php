<?php

declare(strict_types=1);

namespace AstrobinWs;

use GuzzleHttp\Client;

/**
 * Class GuzzleSingleton
 *
 * @package AstrobinWs
 */
class GuzzleSingleton
{
    public const ASTROBIN_URL = 'https://www.astrobin.com/api/v1/';
    public const TIMEOUT = '2.0';

    public const APPLICATION_JSON = 'application/json';

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';

    /**
     * @var Client
     */
    private static $_instance = null;

    /**
     * Build Client instance
     *
     * @return Client
     */
    public static function getInstance(): Client
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Client(['base_uri' => self::ASTROBIN_URL, 'timeout' => self::TIMEOUT]);
        }

        return self::$_instance;
    }
}
