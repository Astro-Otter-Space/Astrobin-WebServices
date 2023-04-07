<?php

declare(strict_types=1);

namespace AstrobinWs\Response\DTO;

use AstrobinWs\Response\AbstractResponse;

/**
 * Class User
 * @package AstrobinWs\Response
 */
final class User extends AbstractResponse implements AstrobinResponse
{
    public int $id;
    public string $username;
    public ?string $about = null;
    public ?string $avatar = null;
    public ?int $image_count = null;
    public ?string $job = null;
    public ?string $hobbies = null;
    public ?string $language = null;
    public ?string $website = null;
}
