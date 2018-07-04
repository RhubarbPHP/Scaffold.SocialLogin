<?php


namespace Rhubarb\Scaffolds\SocialLogin\UseCases;


use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

class DeleteSocialLoginUseCase
{
    /**
     * @param SocialLogin $socialLogin the SocialLogin record to be deleted
     * @throws \Rhubarb\Stem\Exceptions\DeleteModelException
     */
    public static function execute(SocialLogin $socialLogin)
    {
        $socialLogin->delete();
    }
}