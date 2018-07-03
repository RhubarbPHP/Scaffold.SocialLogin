<?php

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\LoginProviders\SocialLoginProvider;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\Authentication\User;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class AuthenticateSocialLoginUseCase
{

    /**
     * @param AuthenticateSocialLoginEntity $loginEntity
     * @return bool
     * @throws ImplementationException
     * @throws LoginFailedException
     * @throws \Rhubarb\Stem\Exceptions\ModelConsistencyValidationException
     * @throws \Rhubarb\Stem\Exceptions\ModelException
     */
    public static function execute(AuthenticateSocialLoginEntity $loginEntity)
    {
        $identityString = $loginEntity->identityString;
        $email = $loginEntity->email;
        $socialNetwork = $loginEntity->socialNetwork;

        if ($socialNetwork == null || $socialNetwork == SocialLogin::SOCIAL_NETWORK_UNKNOWN) {
            throw new ImplementationException();
        }

        try {
            $socialLogin = SocialLogin::findFirst(
                new Equals(SocialLogin::FIELD_IDENTITY_STRING, $identityString),
                new Equals(SocialLogin::FIELD_SOCIAL_NETWORK, $socialNetwork)
            );
            LoginUserForSocialNetworkUseCase::execute($socialLogin);
            return true;
        } catch (RecordNotFoundException $e) {
            if ($email) {
                try {
                    $user = User::findFirst(new Equals("Email", $email));
                } catch (RecordNotFoundException $exception) {
                    $user = new User();
                    $user->Forename = $loginEntity->Forename ?? preg_replace("#@.+$#", "", $email);
                    $user->Email = $email;
                    $user->Username = $loginEntity->Username ?? $user->Forename;
                    $user->save();
                }
                $socialLogin = new SocialLogin();
                $socialLogin->IdentityString = $identityString;
                $socialLogin->SocialNetwork = $socialNetwork;
                $socialLogin->AuthenticationUserID = $user->UniqueIdentifier;
                $socialLogin->save();
                LoginUserForSocialNetworkUseCase::execute($socialLogin);
                Return true;
            }
        }
        return false;
    }
}