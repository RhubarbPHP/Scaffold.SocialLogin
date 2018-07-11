<?php


namespace Rhubarb\Scaffolds\SocialLogin;

use Rhubarb\Crown\DependencyInjection\ProviderInterface;
use Rhubarb\Crown\DependencyInjection\ProviderTrait;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticationSuccessResponseEntity;
use Rhubarb\Stem\Models\Model;

abstract class SocialAuthProvider implements ProviderInterface
{
    use ProviderTrait;

    const
        FAIL_REASON_NO_SOCIAL_NETWORK = 'no social network specified on entity',
        FAIL_REASON_NO_IDENTITY_STRING = 'no identity string specified on entity';

    /**
     *  Can be overriden to do whatever is desired after a successful login.
     * 
     * @param AuthenticateSocialLoginEntity $entity
     */
    public function onSuccess(AuthenticateSocialLoginEntity $entity): AuthenticationSuccessResponseEntity{}

    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @param string                        $failureReason
     */
    abstract public function onFailure(AuthenticateSocialLoginEntity $entity, string $failureReason);

    /**
     * Load a user based on information provided by the authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model||null
     */
    abstract public function loadUser(AuthenticateSocialLoginEntity $entity);

    /**
     * Create and return a new User when they create an account via social media authentication
     *
     * @param AuthenticateSocialLoginEntity $entity
     * @return Model
     */
    abstract public function createUser(AuthenticateSocialLoginEntity $entity): Model;

    /**
     * @param AuthenticateSocialLoginEntity $entity
     * @return mixed
     */
    abstract public function loginUser(AuthenticateSocialLoginEntity $entity);
}