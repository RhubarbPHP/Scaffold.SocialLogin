<?php


namespace Rhubarb\Scaffolds\SocialLogin;


use Rhubarb\Crown\Application;
use Rhubarb\Crown\Module;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Schema\SolutionSchema;

class SocialLoginModule extends Module
{
    public function __construct(string $socialLoginHandlerClassName)
    {
        Application::current()->container()->registerClass(SocialLoginHandler::class, $socialLoginHandlerClassName);
    }

    protected function initialise()
    {
        SolutionSchema::registerSchema(
            'SocialLoginAuthenticationSchema',
            SocialLogin::class
        );
    }
}