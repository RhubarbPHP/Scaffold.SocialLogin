<?php

namespace Rhubarb\Scaffolds\SocialLogin\Models;

use Rhubarb\Stem\Models\Model;
use Rhubarb\Stem\Repositories\MySql\Schema\Columns\MySqlEnumColumn;
use Rhubarb\Stem\Schema\Columns\AutoIncrementColumn;
use Rhubarb\Stem\Schema\Columns\ForeignKeyColumn;
use Rhubarb\Stem\Schema\Columns\StringColumn;
use Rhubarb\Stem\Schema\ModelSchema;

/**
 * Class SocialLogin
 *
 * @property int $SocialLoginID        Repository field
 * @property int $AuthenticationUserID Repository field
 * @property int $SocialNetwork        Repository field
 * @property int $IdentityString       Repository field
 *
 * @package Rhubarb\Scaffolds\Authentication\SocialLogin\Models
 */
class SocialLogin extends Model
{
    const SOCIAL_NETWORK_UNKNOWN = 'unknown';

    const
        FIELD_SOCIAL_LOGIN_ID = "SocialLoginID",
        FIELD_AUTHENTICATION_USER_ID = "AuthenticationUserID",
        FIELD_SOCIAL_NETWORK = "SocialNetwork",
        FIELD_IDENTITY_STRING = "IdentityString"; // The identifier returned by a social network

    /**
     * Returns the schema for this data object.
     *
     * @return \Rhubarb\Stem\Schema\ModelSchema
     * @throws \Rhubarb\Stem\Exceptions\SchemaException
     */
    protected function createSchema()
    {
        $model = new ModelSchema('tblSocialLogin');
        $model->addColumn(
            new AutoIncrementColumn(self::FIELD_SOCIAL_LOGIN_ID),
            new ForeignKeyColumn(self::FIELD_AUTHENTICATION_USER_ID),
            new StringColumn(self::FIELD_SOCIAL_NETWORK, 200, self::SOCIAL_NETWORK_UNKNOWN),
            new StringColumn(self::FIELD_IDENTITY_STRING, 200)
        );

        return $model;
    }
}