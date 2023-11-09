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
    /**
     * @var string
     */
    final public const ASTROBIN_URL = 'https://www.astrobin.com';

    /**
     * @var string
     */
    final public const TIMEOUT = '0';

    /**
     * @var string
     */
    final public const APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    final public const METHOD_GET = 'GET';

    /**
     * @var string
     */
    final public const METHOD_POST = 'POST';

    /**
     * @var string
     */
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
