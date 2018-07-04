<?php

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialLoginHandler;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class AuthenticateSocialLoginUseCase
{

    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @throws ImplementationException
     * @throws \Rhubarb\Stem\Exceptions\ModelConsistencyValidationException
     * @throws \Rhubarb\Stem\Exceptions\ModelException
     */
    public static function execute(AuthenticateSocialLoginEntity $entity)
    {
        $identityString = $entity->identityString;
        if ($identityString == null) {
            SocialLoginHandler::onFailure($entity, SocialLoginHandler::FAIL_REASON_NO_IDENTITY_STRING);
        }

        $socialNetwork = $entity->socialNetwork;
        if ($socialNetwork == null || $socialNetwork == SocialLogin::SOCIAL_NETWORK_UNKNOWN) {
            SocialLoginHandler::onFailure($entity, SocialLoginHandler::FAIL_REASON_NO_SOCIAL_NETWORK);
        }

        try {
            $socialLogin = SocialLogin::findFirst(
                new Equals(SocialLogin::FIELD_IDENTITY_STRING, $identityString),
                new Equals(SocialLogin::FIELD_SOCIAL_NETWORK, $socialNetwork)
            );
        } catch (RecordNotFoundException $e) {
            try {
                $user = SocialLoginHandler::loadUser($entity);
            } catch (RecordNotFoundException $exception) {
                $user = SocialLoginHandler::createUser($entity);
            }
            $socialLogin = new SocialLogin();
            $socialLogin->IdentityString = $identityString;
            $socialLogin->SocialNetwork = $socialNetwork;
            $socialLogin->AuthenticationUserID = $user->UniqueIdentifier;
            $socialLogin->save();
        }
        $entity->socialLogin = $socialLogin;
        SocialLoginHandler::onSuccess($entity);
    }
}