<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

/**
 * Class AstrobinError
 * @package AstrobinWs\Response
 */
final class AstrobinError implements AstrobinResponse
{
    /**
     * AstrobinError constructor.
     */
    public function __construct(public ?string $message)
    {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
