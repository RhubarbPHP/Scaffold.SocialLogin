<?php


namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;

use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\Authentication\User;

trait TestingHelpersTrait
{
    private function getNewUser()
    {
        $user = new User();
        $user->Email = $this->getRandomEmail();
        $user->Username = uniqid('Username:');
        $user->Forename = uniqid('Forename:');
        $user->save();
        return $user;
    }

    private function getNewSocialLogin($userId)
    {
        $socialLogin = new SocialLogin();
        $socialLogin->IdentityString = uniqid();
        $socialLogin->AuthenticationUserID = $userId;
        $socialLogin->SocialNetwork = $this->getRandomSocialNetwork();
        $socialLogin->save();
        return $socialLogin;
    }

    private function getNewSocialAuthEntity($identityString = null, $email = null, $socialNetwork = null)
    {
        $authToken = new AuthenticateSocialLoginEntity();
        $authToken->identityString = $identityString ?? uniqid('Identitystring:');
        $authToken->email = $email ?? $this->getRandomEmail();
        $authToken->socialNetwork = $socialNetwork ?? $this->getRandomSocialNetwork();
        return $authToken;
    }

    private function getRandomSocialNetwork()
    {
        $arr = [
            'facebook',
            'twitter',
            'line',
            'myspace',
            'instagram',
            'github'
        ];
        return $arr[rand(0, 5)];
    }

    private function getRandomEmail()
    {
        return rand(1000, 99999) . '@' . rand(1000, 99999) . '.com';
    }
}