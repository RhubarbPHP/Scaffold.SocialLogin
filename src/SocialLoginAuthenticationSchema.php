<?php

namespace Rhubarb\Scaffolds\SocialLogin;

use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Schema\SolutionSchema;

class SocialLoginAuthenticationSchema extends SolutionSchema
{
    public function __construct(int $version = 1)
    {
        parent::__construct($version);
        $this->addModel('SocialLogin', SocialLogin::class);
    }
}