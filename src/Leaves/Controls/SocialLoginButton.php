<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves\Controls;

use Rhubarb\Crown\Exceptions\ForceResponseException;
use Rhubarb\Crown\Response\RedirectResponse;
use Rhubarb\Leaf\Leaves\Controls\Control;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\UseCases\AuthenticateSocialLoginUseCase;

/**
 * Class SocialLoginButton
 *
 *
 *
 * @package Rhubarb\Scaffolds\Authentication\SocialLogin\Leaves\Controls
 */
abstract class SocialLoginButton extends Control
{
    /** @var SocialLoginButtonModel $model * */
    protected $model;

    protected function createModel()
    {
        return new SocialLoginButtonModel();
    }

    protected function onModelCreated()
    {
        parent::onModelCreated();
        $this->model->attemptSocialLoginEvent->attachHandler(function () {
            $this->attemptSocialLogin();
        });
    }

    abstract protected function createAuthenticateSocialLoginEntity(): AuthenticateSocialLoginEntity;

    /**
     * @param $authenticationEntity
     * @return bool
     * @throws \Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException
     */
    protected function authenticateSocialLogin($authenticationEntity) {
        AuthenticateSocialLoginUseCase::execute($authenticationEntity);
    }

    /**
     * @throws \Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException
     */
    protected function attemptSocialLogin() {
        $entity = $this->createAuthenticateSocialLoginEntity();
        $this->authenticateSocialLogin($entity);
    }
}
