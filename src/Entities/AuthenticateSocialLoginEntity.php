<?php


namespace Rhubarb\Scaffolds\SocialLogin\Entities;


use Rhubarb\Scaffolds\SocialLogin\Models\SocialLogin;

class AuthenticateSocialLoginEntity
{
    /**
     * @var string $identityString
     */
    public $identityString;

    /**
     * @var string $email
     */
    public $email;

    /**
     * The name of the social network
     *
     * @var string $socialNetwork
     */
    public $socialNetwork;

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