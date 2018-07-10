<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 09/07/18
 * Time: 15:48
 */

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;


use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

class CreateSocialLoginUseCase
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
        $socialLogin->IdentityString = $entity->identityString;
        $socialLogin->SocialNetwork = $entity->socialNetwork;
        $socialLogin->AuthenticationUserID = $entity->authenticationUserId;
        $socialLogin->save();

        $entity->socialLoginId = $socialLogin->SocialLoginID;
    }

}