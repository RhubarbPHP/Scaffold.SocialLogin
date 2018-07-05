<?php

namespace Rhubarb\Scaffolds\SocialLogin\Entities;

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