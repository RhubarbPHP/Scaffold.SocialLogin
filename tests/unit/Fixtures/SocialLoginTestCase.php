<?php

namespace Rhubarb\Scaffolds\SocialLogin\Tests\Fixtures;

use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\Scaffolds\SocialLogin\SocialLoginModule;

class SocialLoginTestCase extends RhubarbTestCase
{
    protected $socialLoginProvider;

    protected function setUp()
    {
        $parent = parent::setUp();

        $this->application->registerModule(new SocialLoginModule(SocialLoginProviderTest::class));
        $this->application->initialiseModules();

        return $parent;
    }
}