<?php


namespace Rhubarb\Scaffolds\SocialLogin\Tests\Fixtures;


use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\SocialLoginProvider;
use Rhubarb\Stem\Models\Model;

class SocialLoginProviderTest extends SocialLoginProvider
{
    /**
     * @param AuthenticateSocialLoginEntity $entity
     */
    public function onSuccess(AuthenticateSocialLoginEntity $entity)
    {
        return null;
    }

    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @param string                        $failureReason
     */
    public function onFailure(AuthenticateSocialLoginEntity $entity, string $failureReason)
    {
        return null;
    }

    /**
     * Load a user based on information provided by the authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model
     */
    public function loadUser(AuthenticateSocialLoginEntity $entity): Model
    {
        return new SocialLogin($entity->identityString);
    }

    /**
     * Create and return a new User when they create an account via social media authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model
     */
    public function createUser(AuthenticateSocialLoginEntity $entity): Model
    {
        // All we need is a model with ->getUniqueIdentified for the test of the interface.
        $user = new SocialLogin();
        $user->setUniqueIdentifier($entity->identityString);
        $user->save();
        return $user;
    }
}