<?php

namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\Fixtures\SocialLoginTestCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\CreateSocialLoginUseCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\LoadSocialLoginUseCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\SaveSocialLoginUseCase;

class SaveSocialLoginUseCaseTest extends SocialLoginTestCase
{
    const
        SOCIAL_NETWORK_TWITTER = 'twitter',
        SOCIAL_NETWORK_FACEBOOK = 'facebook';


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

        $testSocialLogin = LoadSocialLoginUseCase::execute($authEntity);
        verify($testSocialLogin->SocialLoginID)->equals($socialLogin->SocialLoginID);
    }

    public function testNewSocialLogin()
    {
        //test new social login and new user
        $authEntity = new AuthenticateSocialLoginEntity();
        $authEntity->identityString = uniqid();
        $authEntity->socialNetwork = uniqid();

        $logins = count(SocialLogin::all());
        $socialLogin = CreateSocialLoginUseCase::execute($authEntity);

        verify(SocialLogin::all()->count())->greaterThan($logins);
    }

}
