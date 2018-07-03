<?php

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\Authentication\User;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;
use Rhubarb\Stem\Filters\GreaterThan;

class LoginUserForSocialNetworkUseCase
{
    /**
     * @param SocialLogin $socialLogin
     * @throws LoginFailedException
     */
    public static function execute($socialLogin)
    {
        $loginProvider = LoginProvider::getProvider();
        try {
            $loginProvider->forceLogin(new User($socialLogin->AuthenticationUserID));
        } catch (RecordNotFoundException $exception) {
            throw new LoginFailedException('Social Login Failed!');
        } catch (ImplementationException $exception) {
            throw $exception;
        }
    }
}