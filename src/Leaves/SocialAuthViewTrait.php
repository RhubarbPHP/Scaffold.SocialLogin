<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticationSuccessResponseEntity;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialAuthProvider;
use Rhubarb\Scaffolds\SocialLogin\UseCases\CreateSocialLoginUseCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\LoadSocialLoginUseCase;

trait SocialAuthViewTrait
{
    /**
     * Override to provide the behaviour that happens when a social authentication occurs.
     *
     * If you need to cause the client to redirect you must return a AuthenticationSuccessResponseEntity with
     * the url property completed.
     *
     * @param SocialLogin $socialLogin
     * @param AuthenticateSocialLoginEntity $loginEntity
     * @return AuthenticationSuccessResponseEntity|null
     */
    protected abstract function onSocialUserAuthenticated(SocialLogin $socialLogin, AuthenticateSocialLoginEntity $loginEntity);

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
        $socialLogin = LoadSocialLoginUseCase::execute($loginEntity);

        if ($socialLogin) {
            $loginEntity->authenticationUserId = $socialLogin->AuthenticationUserID;
        }
        else {
            $socialLogin = CreateSocialLoginUseCase::execute($loginEntity);
        }

        return $this->onSocialUserAuthenticated($socialLogin, $loginEntity);
    }

    protected function registerSocialMediaButtons()
    {
        $buttons = $this->getSocialMediaLoginButtons();
        foreach($buttons as $button){
            $button->userAuthenticatedEvent->attachHandler(function(AuthenticateSocialLoginEntity $loginEntity){
                return $this->onUserAuthenticated($loginEntity);
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