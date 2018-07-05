<?php


namespace Rhubarb\Scaffolds\SocialLogin;


use Rhubarb\Crown\Module;
use Rhubarb\Stem\Schema\SolutionSchema;

class SocialLoginModule extends Module
{
    public function __construct(string $socialLoginProviderClassName)
    {
        SocialAuthProvider::setProviderClassName($socialLoginProviderClassName);
    }

    protected function initialise()
    {
        SolutionSchema::registerSchema(
            'SocialLoginAuthenticationSchema',
            SocialLoginAuthenticationSchema::class
        );
    }
}