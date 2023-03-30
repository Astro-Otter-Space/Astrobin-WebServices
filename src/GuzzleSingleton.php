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
    final public const ASTROBIN_URL = 'https://www.astrobin.com';
    final public const TIMEOUT = '0';

    final public const APPLICATION_JSON = 'application/json';

    final public const METHOD_GET = 'GET';
    final public const METHOD_POST = 'POST';
    final public const METHOD_PUT = 'PUT';

    private static ?Client $_instance = null;

    /**
     * Build Client instance
     */
    public static function getInstance(): Client
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Client(['base_uri' => self::ASTROBIN_URL, 'timeout' => self::TIMEOUT]);
        }

        return self::$_instance;
    }
}
