<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

/**
 * Class User
 * @package AstrobinWs\Response
 */
final class User extends AbstractResponse implements AstrobinResponse
{
    public int $id;
    public string $username;
    public ?string $about;
    public ?string $avatar;
    public ?int $image_count;
    public ?string $job;
    public ?string $hobbies;
    public ?string $language;
    public ?string $website;
}
