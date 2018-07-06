<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves;

use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

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
    abstract protected function onUserAuthenticated(AuthenticateSocialLoginEntity $loginEntity);

    protected function registerSocialMediaButtons()
    {
        $buttons = $this->getSocialMediaLoginButtons();
        foreach($buttons as $button){
            $button->userAuthenticatedEvent->attachHandler(function(AuthenticateSocialLoginEntity $loginEntity){
                $this->onUserAuthenticated($loginEntity);
            });
            $this->registerSubLeaf($button);
        }
//        $this->registerSubLeaf(
//            $buttons
//        );
    }

    protected function printSocialButtons()
    {
        foreach ($this->getSocialMediaLoginButtons() as $socialMediaLoginButton) {
            print $socialMediaLoginButton;
        }
    }
}