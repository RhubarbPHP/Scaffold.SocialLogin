<?php

namespace Rhubarb\Scaffolds\SocialLogin\Entities;

/**
 * An entity to generically store the various information from different social meda api logins
 * 
 * Class AuthenticateSocialLoginEntity
 * @package Rhubarb\Scaffolds\SocialLogin\Entities
 */

class AuthenticateSocialLoginEntity
{
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
     * The ID of the record in our SocialLogin table.
     *
     * @var int $socialLoginId
     */
    public $socialLoginId;

    /**
     * the id of the record in our user authentication table
     * @var int $authenticationUserId
     */
    public $authenticationUserId;
    /**
     * Any additional data needed to be carried over from the SDK implementation
     *
     * @var array $responsePayload
     */
    public $responsePayload;
}