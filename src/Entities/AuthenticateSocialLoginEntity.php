<?php


namespace Rhubarb\Scaffolds\SocialLogin\Entities;


use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

class AuthenticateSocialLoginEntity
{
    /**
     * @var SocialLogin $socialLogin the social login for a verified user
     */
    public $socialLogin;

    /**
     * @var string $identityString
     */
    public $identityString;

    /**
     * The name of the social network
     *
     * @var string $socialNetwork
     */
    public $socialNetwork;

    /**
     * @var string $email
     */
    public $email;

    /**
     * Authentication User Forename
     *
     * @var string $forename
     */
    public $forename;

    /**
     * Authentication User Surname
     *
     * @var string $surname
     */
    public $surname;

    /**
     * Authentication User Username
     *
     * @var string $username
     */
    public $username;

    /**
     * Any additional data needed to be carried over from the SDK implementation
     *
     * @var array $data
     */
    public $data;
}