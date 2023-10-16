<?php

declare(strict_types=1);

namespace AstrobinWs\Exceptions;

/**
 * Class WsException
 *
 * @package Astrobin\Exceptions
 */
class WsException extends \Exception implements \Stringable
{
    /**
     * @var string
     */
    public const KEYS_ERROR = 'API key or API secret are null';

    /**
     * @var string
     */
    public const GUZZLE_RESPONSE = 'Error response Guzzle: %s';

    /**
     * @var string
     */
    public const ERR_READABLE = 'Response not readable';

    /**
     * @var string
     */
    public const ERR_EMPTY = 'Empty response from endPoint "%s"';

    /**
     * @var string
     */
    public const ERR_JSON = 'Not a JSON valid format :\n %s';

    /**
     * @var string
     */
    public const RESP_EMPTY = "Astrobin doesn't find any objects, check your params";

    /**
     * @var string
     */
    public const EMPTY_ID = '%s is not a correct value, alphanumeric expected';

    /**
     * @var string
     */
    public const ERR_DATE_FORMAT = 'Format "%s" is not a correct format for a date, please use YYYY-mm-dd instead';

    /**
     * @var string
     */
    public const ERR_DATE = 'Error date format : \n';

    /**
     * WsException constructor.
     */
    public function __construct(string $message, int $code, ?\Exception $previous)
    {
        parent::__construct(...func_get_args());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return self::class . sprintf('[%d]: %s%s', $this->getCode(), $this->getMessage(), PHP_EOL);
    }
}
