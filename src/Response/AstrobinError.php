<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

/**
 * Class AstrobinError
 * @package AstrobinWs\Response
 */
final class AstrobinError implements AstrobinResponse
{
    public ?string $message;

    /**
     * AstrobinError constructor.
     *
     * @param string|null $message
     */
    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
