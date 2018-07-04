<?php


namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;


use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\SocialLoginTestCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\DeleteSocialLoginUseCase;
use Rhubarb\Stem\Exceptions\DeleteModelException;

class DeleteSocialLoginUseCaseTest extends SocialLoginTestCase
{
    public function testDelete() {
        $socialLogin = $this->getNewSocialLogin(4);
        $socialLoginId = $socialLogin->getUniqueIdentifier();

        verify(SocialLogin::all()->count())->equals(1);
        DeleteSocialLoginUseCase::execute($socialLogin);
        verify(SocialLogin::all()->count())->equals(0);
    }

    public function testDeleteFail() {
        $socialLogin = new SocialLogin();
        $this->expectException(DeleteModelException::class);
        DeleteSocialLoginUseCase::execute($socialLogin);
    }
}