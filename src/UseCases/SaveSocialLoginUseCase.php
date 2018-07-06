<?php

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;

use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialAuthProvider;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class SaveSocialLoginUseCase
{

    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @throws ImplementationException
     * @throws \Rhubarb\Stem\Exceptions\ModelConsistencyValidationException
     * @throws \Rhubarb\Stem\Exceptions\ModelException
     */
    public static function execute(AuthenticateSocialLoginEntity $entity)
    {
        $socialLogin = new SocialLogin();
        $socialLogin->IdentityString = $identityString;
        $socialLogin->SocialNetwork = $socialNetwork;
        $socialLogin->save();
        $identityString = $entity->identityString;
        if ($identityString == null) {
            throw new ImplementationException(SocialLogin::SOCIAL_NETWORK_UNKNOWN);
        }

        $socialNetwork = $entity->socialNetwork;
        if ($socialNetwork == null || $socialNetwork === SocialLogin::SOCIAL_NETWORK_UNKNOWN) {
            throw new ImplementationException(SocialLogin::SOCIAL_NETWORK_UNKNOWN);
        }

        try {
            $socialLogin = SocialLogin::findFirst(
                new Equals(SocialLogin::FIELD_IDENTITY_STRING, $identityString),
                new Equals(SocialLogin::FIELD_SOCIAL_NETWORK, $socialNetwork)
            );
        } catch (RecordNotFoundException $e) {
            $socialLogin = new SocialLogin();
            $socialLogin->IdentityString = $identityString;
            $socialLogin->SocialNetwork = $socialNetwork;
            $socialLogin->save();
        }
        $entity->socialLoginId = $socialLogin->SocialLoginID;
    }
}