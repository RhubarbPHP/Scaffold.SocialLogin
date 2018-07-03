<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves\Controls;

use Rhubarb\Crown\Events\Event;
use Rhubarb\Leaf\Leaves\Controls\ControlModel;

class SocialLoginButtonModel extends ControlModel
{
    public $attemptSocialLoginEvent;

    public function __construct()
    {
        $this->attemptSocialLoginEvent = new Event();
        parent::__construct();
    }
}
