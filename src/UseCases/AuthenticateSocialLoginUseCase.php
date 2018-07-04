<?php

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\Authentication\User;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class AuthenticateSocialLoginUseCase
{

    /**
     * @param AuthenticateSocialLoginEntity $loginEntity
     * @throws ImplementationException
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
//            $user = new User($socialLogin->AuthenticationUserID);
        } catch (RecordNotFoundException $e) {
            try {
                if ($email) {
                    $user = User::findFirst(new Equals("Email", $email));
                } else {
                    throw new RecordNotFoundException(User::class);
                }
            } catch (RecordNotFoundException $exception) {
                $user = self::createNewUser($loginEntity);
            }

            $socialLogin = new SocialLogin();
            $socialLogin->IdentityString = $identityString;
            $socialLogin->SocialNetwork = $socialNetwork;
            $socialLogin->AuthenticationUserID = $user->UniqueIdentifier;
            $socialLogin->save();
        }

        LoginProvider::getProvider()->forceLogin($user);
        return true;
    }

    protected static function createNewUser(AuthenticateSocialLoginEntity $entity)
    {
        $user = new User();
        $user->Email = $entity->email ?? '';
        // TODO: Confirmation page where we get these?
        $user->Username =
            $entity->Username ?? $entity->email ? preg_replace('#@.+$#', '', $entity->email) : 'username';
        $user->Forename = $entity->Forename ?? 'forename';
        $user->Surname = $entity->surname ?? 'surname';
        $user->setNewPassword(uniqid()); // TODO: Confirm and remove
        $user->save();
        return $user;
    }
}