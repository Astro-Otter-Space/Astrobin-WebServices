<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

/**
 * Class AstrobinError
 * @package AstrobinWs\Response
 */
final readonly class AstrobinError implements AstrobinResponse
{
    public function __construct(private ?string $message)
    {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
