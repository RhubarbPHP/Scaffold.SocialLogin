<?php

namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\Authentication\User;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\SocialLoginTestCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\AuthenticateSocialLoginUseCase;
use Rhubarb\Stem\Filters\Equals;

class AuthenticateSocialLoginUseCaseTest extends SocialLoginTestCase
{
    const
        SOCIAL_NETWORK_TWITTER = 'twitter',
        SOCIAL_NETWORK_FACEBOOK = 'facebook';

    public function testAuthenticateExistingUser()
    {
        $socialLogin = $this->getNewSocialLogin(99);
        AuthenticateSocialLoginUseCase::execute($this->getNewSocialAuthEntity($socialLogin->IdentityString));
        verify(LoginProvider::getLoggedInUser())->notNull();

        $user = new User();
        $user->Username = 'laaad';
        $user->Password = 'laaad';
        $user->Email = 'a@b.c';
        $user->Forename = $forename = uniqId('forename-');
        $user->save();

        $uniqId1 = uniqid('SocialIdentifier-');
        verify(AuthenticateSocialLoginUseCase::execute($this->getNewSocialAuthEntity($uniqId1, $user->Email)))->true();
        verify(count(SocialLogin::find(new Equals(SocialLogin::FIELD_AUTHENTICATION_USER_ID,
            $user->getUniqueIdentifier()))))->equals(1);

        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->equals($user->getUniqueIdentifier());

        $user1 = $this->getNewUser();
        $socialLogin = $this->getNewSocialLogin($user1->getUniqueIdentifier());
        $authToken = $this->getNewSocialAuthEntity($socialLogin->IdentityString, $user->Email,
            $socialLogin->SocialNetwork);
        AuthenticateSocialLoginUseCase::execute($authToken);

        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->equals($user1->getUniqueIdentifier());
    }

    public function testCreateNewUser()
    {
        // Test it creates a new user
        AuthenticateSocialLoginUseCase::execute($this->getNewSocialAuthEntity(uniqid('identitystring-'),
            "newuser@email.com"));
        verify(count(User::find(new Equals("Email", "newuser@email.com"))))->equals(1);
    }

    public function testDataCompleteness()
    {
        $user = $this->getNewUser();
        $socialLogin = $this->getNewSocialLogin($user->getUniqueIdentifier());
        AuthenticateSocialLoginUseCase::execute($this->getNewSocialAuthEntity(
            $socialLogin->IdentityString,
            null,
            $socialLogin->SocialNetwork)
        );
        verify($socialLogin->SocialNetwork)->notEquals(SocialLogin::SOCIAL_NETWORK_UNKNOWN);
        $socialLogin = $this->getNewSocialLogin(rand(7, 70));
        $socialLogin->SocialNetwork = self::SOCIAL_NETWORK_TWITTER;
        $socialLogin->save();
        $newEntity = $this->getNewSocialAuthEntity($socialLogin->IdentityString);
        $newEntity->socialNetwork = null;
        try {
            verify(AuthenticateSocialLoginUseCase::execute($newEntity));
            $this->fail('Should throw exception if social network not provided');
        } catch (ImplementationException $exception) {
            verify(true)->true();
        }
    }

    public function testDifferentSocialLoginsForDifferentSocialNetworks()
    {
        $user = $this->getNewUser();
        $user->Email = 'test@test.test';
        $user->save();
        $socialLogin = $this->getNewSocialLogin($user->getUniqueIdentifier());
        $socialLogin->SocialNetwork = self::SOCIAL_NETWORK_TWITTER;
        $socialLogin->save();
        $newEntity = $this->getNewSocialAuthEntity($socialLogin->IdentityString, 'test@test.test');
        $newEntity->socialNetwork = self::SOCIAL_NETWORK_FACEBOOK;
        AuthenticateSocialLoginUseCase::execute($newEntity);
        $socialLogins = SocialLogin::find(new Equals(SocialLogin::FIELD_AUTHENTICATION_USER_ID,
            $user->getUniqueIdentifier()));
        verify($socialLogins->count())->equals(2);
    }

    public function testLogin()
    {
        $user = $this->getNewUser();
        $socialLogin = $this->getNewSocialAuthEntity($user->getUniqueIdentifier());
        AuthenticateSocialLoginUseCase::execute(
            $this->getNewSocialAuthEntity(
                $socialLogin->identityString,
                $user->Email,
                $socialLogin->socialNetwork
            )
        );
        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->equals($user->getUniqueIdentifier());

        $user1 = $this->getNewUser();
        $socialLogin = $this->getNewSocialLogin($user1->getUniqueIdentifier());
        AuthenticateSocialLoginUseCase::execute($this->getNewSocialAuthEntity(null, ''));
        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->notEquals($user->getUniqueIdentifier());
        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->notEquals($user1->getUniqueIdentifier());
    }
}
