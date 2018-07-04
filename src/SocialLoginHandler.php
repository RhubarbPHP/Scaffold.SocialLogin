<?php


namespace Rhubarb\Scaffolds\SocialLogin;

use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Stem\Models\Model;

interface SocialLoginHandler
{
    const
        FAIL_REASON_NO_SOCIAL_NETWORK = 'no social network specified on entity',
        FAIL_REASON_NO_IDENTITY_STRING = 'no identity string specified on entity';

    /**
     * @param AuthenticateSocialLoginEntity $entity
     */
    public static function onSuccess(AuthenticateSocialLoginEntity $entity);

    /**
     *
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @param string                        $failureReason
     */
    public static function onFailure(AuthenticateSocialLoginEntity $entity, string $failureReason);

    /**
     * Load a user based on information provided by the authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model
     */
    public static function loadUser(AuthenticateSocialLoginEntity $entity): Model;

    /**
     * Create and return a new User when they create an account via social media authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model
     */
    public static function createUser(AuthenticateSocialLoginEntity $entity): Model;
}