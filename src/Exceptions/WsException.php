<?php

declare(strict_types=1);

namespace AstrobinWs\Exceptions;

use Throwable;

/**
 * Class WsException
 *
 * @package Astrobin\Exceptions
 */
class WsException extends \Exception
{
    public const KEYS_ERROR = 'API key or API secret are null';

    public const GUZZLE_RESPONSE = 'Error response Guzzle: %s';

    public const ERR_READABLE = 'Response not readable';

    public const ERR_EMPTY = 'Empty response from endPoint "%s"';

    public const ERR_JSON = 'Not a JSON valid format :\n %s';

    public const RESP_EMPTY = 'Astrobin doesn\'t find any objects, check your params';

    public const EMPTY_ID = '%s is not a correct value, alphanumeric expected';

    public const ERR_DATE_FORMAT = 'Format "%s" is not a correct format for a date, please use YYYY-mm-dd instead';

    public const ERR_DATE = 'Error date format : \n';

    /**
     * WsException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(string $message, int $code, ?\Exception $previous)
    {
        parent::__construct(...func_num_args());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . "[{$this->getCode()}]: {$this->getMessage()}\n";
    }
}
