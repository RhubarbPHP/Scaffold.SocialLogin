<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves;

use Rhubarb\Leaf\Leaves\LeafDeploymentPackage;
use Rhubarb\Scaffolds\Authentication\Leaves\LoginView;
use Rhubarb\Scaffolds\SocialLogin\Entities\AuthenticateSocialLoginEntity;
use Rhubarb\Scaffolds\SocialLogin\Leaves\Controls\SocialLoginButton;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

/**
 * An example Login View using the patterns from the SocialAuthViewTrait
 */
abstract class SocialLoginView extends LoginView
{
    use SocialAuthViewTrait;

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
    }}
