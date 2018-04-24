<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 19/04/18
 * Time: 23:41
 */

namespace HamhamFonfon\Astrobin\Exceptions;
use Throwable;

/**
 * Class astroBinException
 * @package AppBundle\Astrobin\Exceptions
 */
class AstrobinException extends \Exception
{

    /**
     * astroBinException constructor.
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