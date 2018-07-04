<?php

namespace Rhubarb\Scaffolds\SocialLogin\Exceptions;

use Rhubarb\Crown\Exceptions\RhubarbException;

class SocialLoginFailedException extends RhubarbException
{
    public function __construct(string $privateMessage = "", \Exception $previous = null)
    {
        parent::__construct($privateMessage, $previous);

        $this->publicMessage = "Failed to authenticate social login attempt with social media provider.";
    }
}