<?php

namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\Fixtures\SocialLoginTestCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\SaveSocialLoginUseCase;

class SaveSocialLoginUseCaseTest extends SocialLoginTestCase
{
    const
        SOCIAL_NETWORK_TWITTER = 'twitter',
        SOCIAL_NETWORK_FACEBOOK = 'facebook';

    public function testEntityData()
    {
        $authEntity = new AuthenticateSocialLoginEntity();
        $this->expectException(ImplementationException::class);
        SaveSocialLoginUseCase::execute($authEntity);

        $authEntity->identityString = uniqid();
        $this->expectException(ImplementationException::class);
        SaveSocialLoginUseCase::execute($authEntity);

        $authEntity->socialNetwork = self::SOCIAL_NETWORK_FACEBOOK;
        SaveSocialLoginUseCase::execute($authEntity);
    }

    public function testExistingSocialLogin()
    {
        $user = new SocialLogin();
        $user->save();

        $socialLogin = new SocialLogin();
        $socialLogin->IdentityString = uniqid();
        $socialLogin->SocialNetwork = self::SOCIAL_NETWORK_FACEBOOK;
        $socialLogin->AuthenticationUserID = $user->getUniqueIdentifier();
        $socialLogin->save();

        $authEntity = new AuthenticateSocialLoginEntity();
        $authEntity->identityString = $socialLogin->IdentityString;
        $authEntity->socialNetwork = $socialLogin->SocialNetwork;

        SaveSocialLoginUseCase::execute($authEntity);
        verify($authEntity->authenticationUserId)->equals($user->getUniqueIdentifier());
    }

    public function testNewSocialLogin()
    {
        //test new social login and new user
        $authEntity = new AuthenticateSocialLoginEntity();
        $authEntity->identityString = uniqid();
        $authEntity->socialNetwork = uniqid();
        SaveSocialLoginUseCase::execute($authEntity);

        $userId = null;

        $authEntity = new AuthenticateSocialLoginEntity();
        $authEntity->identityString = uniqid();
        $authEntity->socialNetwork = uniqid();
        $logins = count(SocialLogin::all());
        SaveSocialLoginUseCase::execute($authEntity);
        verify($authEntity->authenticationUserId)->notEmpty();
        verify(SocialLogin::all()->count())->greaterThan($logins);
    }

}
