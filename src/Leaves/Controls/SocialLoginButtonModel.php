<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves\Controls;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Leaf\Leaves\Controls\ControlModel;

abstract class SocialLoginButtonModel extends ControlModel
{
    public $attemptSocialLoginEvent;

    public $clientSideLoginInfo;
    
    public $requiredFields;

    public $type = 'submit';

    public function __construct()
    {
        $this->attemptSocialLoginEvent = new Event();
        parent::__construct();
        
        $this->requiredFields = static::getRequiredFields();
    }
    
    abstract protected function getRequiredFields():string;

    protected function getExposableModelProperties()
    {
        return array_merge(parent::getExposableModelProperties(), ['requiredFields']);
    }


}
