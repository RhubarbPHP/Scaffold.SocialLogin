<?php

namespace Rhubarb\Scaffolds\SocialLogin\Leaves\Controls;

use Rhubarb\Leaf\Leaves\Controls\ControlView;

/**
 * Class SocialLoginButtonView
 *
 * @property SocialLoginButtonModel $model
 *
 * @package Rhubarb\Scaffolds\SocialLogin\Leaves\Controls
 */
class SocialLoginButtonView extends ControlView
{
    protected function printViewContent()
    {
        $classes = $this->model->getClassAttribute();
        $otherAttributes = $this->model->getHtmlAttributes();
        $this->model->type = "button";
        $xhrAttribute = ' xmlrpc="yes"';
        $text = htmlentities($this->model->text);

        print <<<HTML
        <input 
            leaf-name="{$this->model->leafName}" 
            type="{$this->model->type}" 
            name="{$this->model->leafPath}"
            id="{$this->model->leafPath}" 
            value="{$text}"
            {$classes} 
            {$otherAttributes} 
            {$xhrAttribute} 
        />
HTML;
    }
}
