<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves;

use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialAuthProvider;
use Rhubarb\Scaffolds\SocialLogin\UseCases\CreateSocialLoginUseCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\LoadSocialLoginUseCase;

trait SocialAuthViewTrait
{
    /**
     * Returns an array of SocialLoginButtons to be included in the login screen.
     *
     * @return SocialLoginButton[]
     */
    abstract protected function getSocialMediaLoginButtons(): array;

    
    /**
     * Called when a user authenticates.
     *
     * Most often this is used to find or create a user and then attached the AuthenticationUserID
     *
     * @param SocialLogin $socialLogin
     * @return mixed
     */

    /**
     * Called when a user authenticates.
     *
     * Most often this is used to find or create a user and then attached the AuthenticationUserID
     * @param AuthenticateSocialLoginEntity $loginEntity
     * @return mixed|void
     * @throws \Rhubarb\Stem\Exceptions\RecordNotFoundException
     */
    protected function onUserAuthenticated(AuthenticateSocialLoginEntity $loginEntity)
    {
        $authProvider = SocialAuthProvider::getProvider();
        $socialLogin = LoadSocialLoginUseCase::execute($loginEntity);
        if ($socialLogin) {
            $loginEntity->authenticationUserId = $socialLogin->AuthenticationUserID;
        }
        else {
            $user =$authProvider->loadUser($loginEntity);
            if(!$user)
            {
                $user = $authProvider->createUser($loginEntity);
            }
            $loginEntity->authenticationUserId = $user->getUniqueIdentifier();
            $socialLogin = CreateSocialLoginUseCase::execute($loginEntity);
        }
        $authProvider->loginUser($loginEntity);
        //$authProvider->onSuccess($loginEntity);
    }

    protected function registerSocialMediaButtons()
    {
        $buttons = $this->getSocialMediaLoginButtons();
        foreach($buttons as $button){
            $button->userAuthenticatedEvent->attachHandler(function(AuthenticateSocialLoginEntity $loginEntity){
                
                $this->onUserAuthenticated($loginEntity);
            });
            $this->registerSubLeaf($button);
        }
    }

    protected function printSocialButtons()
    {
        foreach ($this->getSocialMediaLoginButtons() as $socialMediaLoginButton) {
            print $socialMediaLoginButton;
        }
    }
}