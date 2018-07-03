<?php
/**
 * Created by PhpStorm.
 * User: seanmcgarrity
 * Date: 03/07/2018
 * Time: 11:37
 */

namespace Rhubarb\Scaffolds\SocialLogin\UseCases;


use Rhubarb\Crown\LoginProviders\Exceptions\LoginFailedException;
use Rhubarb\Crown\Tests\Fixtures\TestCases\RhubarbTestCase;
use Rhubarb\Scaffolds\Authentication\LoginProviders\LoginProvider;
use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\UseCases\TestingHelpersTrait;

class LoginUserForSocialNetworkUseCaseTest extends RhubarbTestCase
{
    use TestingHelpersTrait;

    public function testExecute()
    {
        $socialLogin = new SocialLogin();
        $socialLogin->AuthenticationUserID = 6;
        $socialLogin->save();
        try {
            LoginUserForSocialNetworkUseCase::execute($socialLogin);
            $this->fail();
        } catch (LoginFailedException $exception) {
            verify(true)->true();
        }

        $user = $this->getNewUser();

        $socialLogin = new SocialLogin();
        $socialLogin->AuthenticationUserID = $user->getUniqueIdentifier();
        $socialLogin->save();

        LoginUserForSocialNetworkUseCase::execute($socialLogin);
        verify(LoginProvider::getLoggedInUser()->getUniqueIdentifier())->equals($user->getUniqueIdentifier());

    }

}
