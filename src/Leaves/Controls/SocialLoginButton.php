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
                //createSocialLoginEntity is abstract, implemented by specific social login buttons
                $entity = $this->createAuthenticateSocialLoginEntity($this->model->clientSideLoginInfo);
                return $this->userAuthenticatedEvent->raise($entity);
            } else {
                $this->handleSocialLoginFailed(new SocialLoginFailedException);
            }
        });
    }

    /**
     * Can be overriden to handle social login failures however you like
     *
     * @param SocialLoginFailedException $exception
     * @throws SocialLoginFailedException
     */
    protected function handleSocialLoginFailed(SocialLoginFailedException $exception)
    {
        throw $exception;
    }

    /**
     * Must be implemented for each child Social Login Button, eg Facebook, and extract the data required by the
     *
     * AuthenticateSocialLoginEntity from the loginInfo retrieved from your social media's API.
     * 
     * @param $loginInfo
     * @return AuthenticateSocialLoginEntity
     */
    abstract protected function createAuthenticateSocialLoginEntity($loginInfo): AuthenticateSocialLoginEntity;

    /**
     * Must be implemented by each child Social Login button, eg Facebook
     *
     * To prevent account hijacking, we must check that the access token retrieved from the Javascript API
     *
     * is actually the token for the logged in social media user.
     *
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
     * Extract the social media access token from the login info passed through from javascript and return it
     * 
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
