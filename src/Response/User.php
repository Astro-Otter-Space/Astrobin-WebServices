<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

/**
 * Class User
 * @package AstrobinWs\Response
 */
final class User extends AbstractResponse implements AstrobinResponse
{
    /** @var int */
    public $id;
    /** @var string */
    public $username;
    /** @var string */
    public $avatar;
    /** @var int */
    public $image_count;
    /** @var string */
    public $job;
    /** @var string */
    public $hobbies;
    /** @var string */
    public $language;
    /** @var string */
    public $website;
}