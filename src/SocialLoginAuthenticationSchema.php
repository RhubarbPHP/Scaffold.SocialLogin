<?php

namespace Rhubarb\Scaffolds\SocialLogin;

use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Schema\SolutionSchema;
use Rhubarb\Scaffolds\SocialLogin\Leaves\SocialAuthViewTrait;

class SocialLoginAuthenticationSchema extends SolutionSchema
{
    //use SocialAuthViewTrait;
    
    public function __construct(int $version = 1)
    {
        parent::__construct($version);
        $this->addModel('SocialLogin', SocialLogin::class);
    }
}