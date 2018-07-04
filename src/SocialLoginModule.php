<?php


namespace Rhubarb\Scaffolds\SocialLogin;


use Rhubarb\Crown\Module;
use Rhubarb\Scaffolds\Authentication\AuthenticationModule;
use Rhubarb\Stem\Schema\SolutionSchema;

class SocialLoginModule extends Module
{
    private $socialLoginModelClass;
    private $loginProviderClass;

    public function __construct($loginProviderClass, $socialLoginSchemaClass = SocialLoginAuthenticationSchema::class)
    {
        $this->loginProviderClass = $loginProviderClass;
        $this->socialLoginModelClass = $socialLoginSchemaClass;
    }

    protected function initialise()
    {
        SolutionSchema::registerSchema(
            'SocialLoginAuthenticationSchema',
            $this->socialLoginModelClass
        );
    }

    protected function getModules()
    {
        return [new AuthenticationModule($this->loginProviderClass)];
    }
}