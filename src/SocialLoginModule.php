<?php


namespace Rhubarb\Scaffolds\SocialLogin;


use Rhubarb\Crown\Application;
use Rhubarb\Crown\Module;
use Rhubarb\Scaffolds\Authentication\AuthenticationModule;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Schema\SolutionSchema;

class SocialLoginModule extends Module
{
    private $socialLoginModelClass;

    public function __construct($socialLoginModelClass)
    {
        $this->socialLoginModelClass = $socialLoginModelClass;
    }

    protected function initialise()
    {
        SolutionSchema::registerSchema(
            'SocialLoginAuthenticationSchema',
            $this->socialLoginModelClass ?? SocialLogin::class
        );
    }


    protected function getModules()
    {
        /** @var Module $module */
        foreach (Application::current()->getModules() as $module) {
            if (strpos($module->getModuleName(), "Authentication")) {
                $authenticationModule = $module;
                break;
            }
        }
        $modules[] = $authenticationModule ? [$authenticationModule] : [new AuthenticationModule(LoginProvider::class)];
        return $modules;
    }
}