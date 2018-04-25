<?php

namespace Astrobin\Exceptions;
use Throwable;


/**
 * Class WsException
 * @package Astrobin\Exceptions
 */
class WsException extends \Exception
{

    /**
     * WsException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . "[{$this->getCode()}]: {$this->getMessage()}\n";
    }
}