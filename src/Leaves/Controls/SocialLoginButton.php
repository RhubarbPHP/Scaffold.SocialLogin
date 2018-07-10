<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves\Controls;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Leaf\Leaves\Controls\Control;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Exceptions\SocialLoginFailedException;
use Rhubarb\Scaffolds\SocialLogin\SocialAuthProvider;
use Rhubarb\Scaffolds\SocialLogin\UseCases\SaveSocialLoginUseCase;

/**
 * Class SocialLoginButton
 * @property SocialLoginButtonModel $model
 * @package Rhubarb\Scaffolds\Authentication\SocialLogin\Leaves\Controls
 */
abstract class SocialLoginButton extends Control
{

    /**
     * Raised to allow the hosting application to respond to an authentication
     *
     * Should return an AuthenticationSuccessResponseEntity to let the client side know where to take the user
     *
     * @var Event 
     */
    public $userAuthenticatedEvent;

    public function __construct(?string $name = null, ?callable $initialiseModelBeforeView = null)
    {
        parent::__construct($name, $initialiseModelBeforeView);

        $this->userAuthenticatedEvent = new Event();
    }

    protected function onModelCreated()
    {
        parent::onModelCreated();
        $this->model->attemptSocialLoginEvent->attachHandler(function ($loginInfo) {
            $this->model->clientSideLoginInfo = $loginInfo;
            if ($this->validateAuthToken()) {
                $entity = $this->createAuthenticateSocialLoginEntity($this->model->clientSideLoginInfo);
                return $this->userAuthenticatedEvent->raise($entity);
            } else {
                $this->handleSocialLoginFailed(new SocialLoginFailedException);
            }
        });
    }

    /**
     * @param LoginFailedException $exception
     * @throws LoginFailedException
     */
    protected function handleLoginFailed(LoginFailedException $exception)
    {
        throw $exception;
    }

    /**
     * @param SocialLoginFailedException $exception
     * @throws SocialLoginFailedException
     */
    protected function handleSocialLoginFailed(SocialLoginFailedException $exception)
    {
        throw $exception;
    }

    /**
     * @param $loginInfo
     * @return AuthenticateSocialLoginEntity
     */
    abstract protected function createAuthenticateSocialLoginEntity($loginInfo): AuthenticateSocialLoginEntity;

    /**
     * @param $authenticationEntity
     * @throws \Rhubarb\Crown\Exceptions\ImplementationException
     * @throws \Rhubarb\Stem\Exceptions\ModelConsistencyValidationException
     * @throws \Rhubarb\Stem\Exceptions\ModelException
     */
    protected function saveSocialLogin($authenticationEntity)
    {
        SaveSocialLoginUseCase::execute($authenticationEntity);
    }

    /**
     * @param $login
     * @return bool
     */
    abstract protected function serverSideValidateToken($login): bool;

    /**
     * @throws LoginFailedException
     * @throws \Rhubarb\Crown\Exceptions\ImplementationException
     * @throws \Rhubarb\Stem\Exceptions\ModelConsistencyValidationException
     * @throws \Rhubarb\Stem\Exceptions\ModelException
     */
    protected function validateAuthToken()
    {
        return ($this->serverSideValidateToken($this->getSocialMediaLoginToken()));
    }

    /**
     * @param $loginInfo
     * @return string
     * @throws LoginFailedException
     */
    abstract protected function getSocialMediaLoginToken(): string;

    /**
     * Used to set the fields for ths social media api that requests user data.
     * 
     * @param array $requiredFields
     */
    public function setRequiredFields(array $requiredFields) {
        $this->model->requiredFields = $requiredFields;
    }

}
