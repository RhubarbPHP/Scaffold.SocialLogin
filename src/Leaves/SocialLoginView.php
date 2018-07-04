<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves;

use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;
use Rhubarb\Scaffolds\Authentication\Leaves\LoginView;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;

abstract class SocialLoginView extends LoginView
{
    /** @var SocialLoginModel $model * */
    protected $model;

    public function createSubLeaves()
    {
        parent::createSubLeaves();
        $this->registerSocialMediaButtons();
    }

    public function printViewContent()
    {
        parent::printViewContent();
        $this->printSocialButtons();
    }

    protected function printSocialButtons()
    {
        foreach ($this->getSocialMediaLoginButtons() as $socialMediaLoginButton) {
            print $socialMediaLoginButton;
        }
    }

    public function getDeploymentPackage()
    {
        return new LeafDeploymentPackage(__DIR__ . '/SocialLoginViewBridge.js');
    }

    protected function getViewBridgeName()
    {
        return 'SocialLoginViewBridge';
    }

    /**
     * Returns an array of SocialLoginButtons to be included in the login screen.
     *
     * @return SocialLoginButton[]
     */
    abstract protected function getSocialMediaLoginButtons(): array;

    protected function registerSocialMediaButtons()
    {
        $this->registerSubLeaf($this->getSocialMediaLoginButtons());
    }
}
