<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 09/07/18
 * Time: 15:45
 */

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;


use Rhubarb\Crown\Exceptions\ImplementationException;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Stem\Exceptions\RecordNotFoundException;
use Rhubarb\Stem\Filters\Equals;

class LoadSocialLoginUseCase
{
    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @return bool|SocialLogin|\Rhubarb\Stem\Models\Model
     */
    public static function execute(AuthenticateSocialLoginEntity $entity)
    {
        try {
            $socialLogin = SocialLogin::findFirst(
                new Equals(SocialLogin::FIELD_IDENTITY_STRING, $entity->identityString),
                new Equals(SocialLogin::FIELD_SOCIAL_NETWORK, $entity->socialNetwork)
            );
            $entity->socialLoginId = $socialLogin->SocialLoginID;
            return $socialLogin;
        } catch (\Exception $e) {
            return false;
        }
    }
}