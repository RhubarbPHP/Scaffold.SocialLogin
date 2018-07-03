<?php

namespace Rhubarb\Scaffolds\SocialLogin;

use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

class SocialLoginAuthenticationSchema extends \Rhubarb\Stem\Schema\SolutionSchema
{
    public function __construct(int $version = 1)
    {
        parent::__construct($version);

        $this->addModel('SocialLogin', SocialLogin::class);
    }
}