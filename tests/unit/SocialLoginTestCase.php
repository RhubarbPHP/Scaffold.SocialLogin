<?php

namespace Rhubarb\Scaffolds\SocialLogin\Tests;

use Rhubarb\Crown\Encryption\Aes256ComputedKeyEncryptionProvider;
use Rhubarb\Crown\Encryption\EncryptionProvider;
use Rhubarb\Crown\Encryption\HashProvider;
use Rhubarb\Crown\Encryption\Sha512HashProvider;
use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\Authentication\User;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialLoginModule;

class SocialLoginTestCase extends RhubarbTestCase
{
    protected function setUp()
    {
        $parent = parent::setUp();
        $this->application->registerModule(new SocialLoginModule(LoginProvider::class));
        $this->application->initialiseModules();

        HashProvider::setProviderClassName(Sha512HashProvider::class);
        EncryptionProvider::setProviderClassName(Aes256ComputedKeyEncryptionProvider::class);

        return $parent;
    }

    protected function getNewUser()
    {
        $user = new User();
        $user->Email = $this->getRandomEmail();
        $user->Username = uniqid('Username:');
        $user->Forename = uniqid('Forename:');
        $user->save();
        return $user;
    }

    protected function getNewSocialLogin($userId)
    {
        $socialLogin = new SocialLogin();
        $socialLogin->IdentityString = uniqid();
        $socialLogin->AuthenticationUserID = $userId;
        $socialLogin->SocialNetwork = $this->getRandomSocialNetwork();
        $socialLogin->save();
        return $socialLogin;
    }

    protected function getNewSocialAuthEntity($identityString = null, $email = null, $socialNetwork = null)
    {
        $authToken = new AuthenticateSocialLoginEntity();
        $authToken->identityString = $identityString ?? uniqid('Identitystring:');
        $authToken->email = $email ?? $this->getRandomEmail();
        $authToken->socialNetwork = $socialNetwork ?? $this->getRandomSocialNetwork();
        return $authToken;
    }

    protected function getRandomSocialNetwork()
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

    protected function getRandomEmail()
    {
        return rand(1000, 99999) . '@' . rand(1000, 99999) . '.com';
    }
}