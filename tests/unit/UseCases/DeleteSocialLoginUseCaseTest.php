<?php


namespace Rhubarb\Scaffolds\SocialLogin\Tests\UseCases;

use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;
use Rhubarb\Scaffolds\SocialLogin\Tests\Fixtures\SocialLoginTestCase;
use Rhubarb\Scaffolds\SocialLogin\UseCases\DeleteSocialLoginUseCase;
use Rhubarb\Stem\Exceptions\DeleteModelException;

class DeleteSocialLoginUseCaseTest extends SocialLoginTestCase
{
    public function testDelete() {
        $socialLogin = new SocialLogin();
        $socialLogin->IdentityString = uniqid();
        $socialLogin->SocialNetwork = uniqid();
        $socialLogin->save();
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